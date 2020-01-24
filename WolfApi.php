<?php


class WolfApi{

    protected $apiKey;
    protected $idLang;
    protected $dev;

    /**
     * WolfApi constructor.
     * @param $apiKey - Ваш Ключ API который
     * @param int $id_lang - id языка, на котором будут возвращаться ответы. По умолчанию 1 - русский.
     * @param bool $devMode - Если true - то в ответе будет показаны данные запроса, которые пришли на сервер.
     */
    function __construct($apiKey, $id_lang = 1, $devMode = false) {
        $this->apiKey = $apiKey;
        $this->idLang = (int)$id_lang;
        $this->dev = $devMode;
    }

    public function getB2CPrice(){
        return $this->execute('Price', 'getOffsetPrice', ['type' => 'b2c']);
    }
    public function getB2BPrice(){
        return $this->execute('Price', 'getOffsetPrice', ['type' => 'b2b']);
    }

    /**
     * @return mixed - Возвращает массив доступных на сервере языков.
     */
    public function getLanguages(){
        return $this->execute('Languages', 'getAllLanguages');
    }

    /**
     * @return mixed - Возвращает массив Покрытий для продуктов
     */
    public function getAllCover(){
        return $this->execute('Cover', 'getAllCover');
    }

    /**
     * @return mixed - Возвращает массив Печатных Материалов
     */
    public function getAllMaterial(){
        return $this->execute('Material', 'getAllMaterial');
    }

    /**
     * @return mixed - Возвращает массив Продуктов
     */
    public function getAllProduct(){
        return $this->execute('Product', 'getAllProduct');
    }

    /**
     * @param string $model - Имя модели;
     * @param string $method - Имя метода;
     * @param mixed $param - дополнительные параметры (если нужны);
     * @return mixed
     */
    public function execute($model, $method, $param = array()){
        $param['id_lang'] = $this->idLang;
        return $this->request(['model' => $model, 'method' => $method, 'param' => $param]);
    }

    private function request($data){

        $url = 'https://api.wolf.ua/v1/';
        $data['dev'] = $this->dev;
        $data = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'APIKey: '.$this->apiKey));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}