<?php


class WolfApi{

    protected $apiKey;
    protected $dev = true; // Показывает, какие данные пришли на сервер

    function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function getLanguages(){
        return $this->execute('languages', 'getAll');
    }

    public function getLangById($id){
        return $this->execute('languages', 'getLangById', $id);
    }

    /**
     * @param string $model - Имя модели;
     * @param string $method - Имя метода;
     * @param mixed $data - дополнительные данные (если нужны);
     * @return mixed
     */
    public function execute($model, $method, $data = ''){
        return $this->request(['model' => $model, 'method' => $method, 'data' => $data]);
    }

    private function request($data){
        $url = 'http://dev.api.wolf.ua/v1/get';
        $data['dev'] = $this->dev;
        $data = json_encode($data);
        $data = base64_encode($data);

        $ch = curl_init($url.'?data='.$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('APIKey : '.$this->apiKey));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}