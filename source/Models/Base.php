<?php declare(strict_types=1);

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;
use League\Fractal\Manager;
use League\Fractal\Serializer\JsonApiSerializer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Source\Libs\TableTransformer;

class Base extends DataLayer
{
    public function __construct( string $table, array $params)
    {
        parent::__construct($table, $params);
    }

    public function save(): bool
    {
        return parent::save();
    }

    public function getAllData(): void
    {
        $fractal = new Manager();
        $fractal->setSerializer(new JsonApiSerializer());
        $datas = $this->find()->fetch(true) ?? [];
        $data = [];
        if (count($datas)>0){
            for ($i=0;$i<count($datas);$i++) {
                $step[$i] = json_encode($datas[$i]->data);
                $step = json_decode($step[$i], true);
                array_push($data, $step);
            }
            $resource = new Collection($data, new TableTransformer());
            echo $fractal->createData(($resource))->toJson();
        } else {
            echo 'null';
        }
    }

    public function getData($args): void
    {
        $fractal = new Manager();
        $fractal->setSerializer(new JsonApiSerializer());
        $id = filter_var((int)$args['id'], FILTER_VALIDATE_INT);
        if ($this->findById($id)){
            $table = json_decode(json_encode($this->findById($id)->data),true);
            $resource = new Item($table, new TableTransformer());
            echo $fractal->createData(($resource))->toJson();
        } else {
            echo 'null';
        }
    }

    public function createData($params): void
    {
        foreach($params as $key => $value) {
            $this->$key = $value;
        }

        if($this->save()) {
            echo "<h2>Cadastrado com sucesso: {$this->id}</h2>";
        } else {
            echo "<h2>{$this->fail()->getMessage()}</h2>";
        }
    }

    public function updateData($args, $params)
    {
        $table = $this->findById(filter_var((int)$args['id'], FILTER_VALIDATE_INT));

        foreach($params as $key => $value) {
            $table->$key = $value;
        }

        if($table->save()) {
            echo "<h2>Dados Atualizados com sucesso: {$table->id}</h2>";
        } else {
            echo "<h2>{$table->fail()->getMessage()}</h2>";
        }
    }

    public function deleteData($args): bool
    {
        $data = $this->findById(filter_var((int)$args['id'], FILTER_VALIDATE_INT));
        if (!empty($data->url_image) || !empty($data->avatar)) {
            unlink($data->url_image ?? $data->avatar);
        }
        $result = $data->destroy();
        return $result;
    }
}