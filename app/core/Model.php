<?php
/**
 * Model.php — Base model dengan JSON file locking
 * Semua model turunan wajib lewat class ini.
 */
abstract class Model
{
    protected string $jsonFile;
    protected string $primaryKey = 'id';

    public function __construct(string $jsonFile)
    {
        $this->jsonFile = DB_PATH . '/' . $jsonFile . '.json';
        if (!file_exists($this->jsonFile)) {
            $dir = dirname($this->jsonFile);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            file_put_contents($this->jsonFile, json_encode([]));
        }
    }

    protected function readAll(): array
    {
        $fp = fopen($this->jsonFile, 'r');
        if (!$fp) return [];
        flock($fp, LOCK_SH);
        $content = stream_get_contents($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
        return json_decode($content, true) ?: [];
    }

    protected function writeAll(array $data): bool
    {
        $fp = fopen($this->jsonFile, 'c');
        if (!$fp) return false;
        if (flock($fp, LOCK_EX)) {
            ftruncate($fp, 0);
            rewind($fp);
            fwrite($fp, json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            fflush($fp);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
        return true;
    }

    public function all(): array
    {
        return $this->readAll();
    }

    public function find($id): ?array
    {
        foreach ($this->readAll() as $row) {
            if (isset($row[$this->primaryKey]) && $row[$this->primaryKey] == $id) {
                return $row;
            }
        }
        return null;
    }

    public function where(array $conditions): array
    {
        return array_values(array_filter($this->readAll(), function ($row) use ($conditions) {
            foreach ($conditions as $key => $value) {
                if (!isset($row[$key]) || $row[$key] != $value) return false;
            }
            return true;
        }));
    }

    public function whereIn(string $key, array $values): array
    {
        return array_values(array_filter($this->readAll(), fn($row) => in_array($row[$key] ?? null, $values)));
    }

    public function insert(array $data): array
    {
        $rows = $this->readAll();
        $data[$this->primaryKey] = $this->nextId($rows);
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        $rows[] = $data;
        $this->writeAll($rows);
        return $data;
    }

    public function update($id, array $data): bool
    {
        $rows = $this->readAll();
        $found = false;
        foreach ($rows as &$row) {
            if (isset($row[$this->primaryKey]) && $row[$this->primaryKey] == $id) {
                $row = array_merge($row, $data);
                $row['updated_at'] = date('Y-m-d H:i:s');
                $found = true;
            }
        }
        unset($row);
        if (!$found) return false;
        return $this->writeAll($rows);
    }

    public function delete($id): bool
    {
        $rows = array_filter($this->readAll(), fn($r) => $r[$this->primaryKey] != $id);
        return $this->writeAll($rows);
    }

    public function count(array $conditions = []): int
    {
        return count(empty($conditions) ? $this->all() : $this->where($conditions));
    }

    private function nextId(array $rows): int
    {
        if (empty($rows)) return 1;
        $ids = array_filter(array_column($rows, $this->primaryKey), 'is_numeric');
        return empty($ids) ? 1 : (int)max($ids) + 1;
    }
}
