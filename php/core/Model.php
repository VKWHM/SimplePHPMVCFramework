<?php
namespace app\core;
use ReflectionClass;
use Reflectionattribute;
use Exception;
class ValidateException extends Exception {}
abstract class Model {
    private $rClass;
    private $methods;
    private $columns;
    abstract public function tableName();
    abstract public function primaryKey();

    public $attrs;

    public function filter(array $params) {
        $table = $this->tableName();
        $conditions = implode("AND ", array_map(fn($attr) => "$attr = :$attr", array_keys($params)));
        $sql = "SELECT * FROM $table WHERE $conditions;";
        $stmt = Application::getInstance()->db->pdo->prepare($sql);
        $stmt->execute($params);
        if (!$stmt->rowCount()) {
            return false;
        }
        return $stmt->fetchObject(static::class);
    }

    private function attributes() {
        $attrs =  array_filter($this->rClass->getProperties(), function ($attribute) {
            return $attribute->class === $this->rClass->getName();
        });
        return array_map(fn($attr) => $attr->name, $attrs);
    }
    private function getMethods() {
        $methods = array_filter($this->rClass->getMethods(), function ($method) {
            return $method->class === $this->rClass->getName();
        });
        return array_map(fn($method) => $method->name, $methods);
    }


    public function __construct() {
        $this->rClass = new ReflectionClass($this);
        $this->columns = Application::getInstance()->db->tableColumns($this->tableName());
    }

    public function load(array $data) {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->columns)) {
                $this->$key = $value;
                $this->attrs[] = $key;
            }
        }
    }

    public function save() {
        $attrs = $this->attrs;
        // try {
            $params = array_map(fn($item) => ":$item", $attrs);
            $stmt = Application::getInstance()->db->pdo->prepare(
                "INSERT INTO ". $this->tableName() ." (". implode(", ", $attrs) .") VALUES (". implode(", ", $params) .");"
            )   ;
            foreach ($attrs as $attr) {
                $stmt->bindValue(":$attr", $this->{$attr});
            }
            $stmt->execute();
            return true;
        // } catch(Exception $e) {
        //     return false;
        // }

    }
}
 ?>
