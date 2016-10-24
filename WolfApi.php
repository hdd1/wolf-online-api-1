<?php


class WolfApi{

    protected $apiKey;
    protected $idLang;
    protected $dev = true; // Показывает, какие данные пришли на сервер

    /**
     * WolfApi constructor.
     * @param $apiKey - Ваш Ключ API который
     * @param int $id_lang - id языка, на котором будут возвращаться ответы. По умолчанию 1 - русский.
     */
    function __construct($apiKey, $id_lang = 1) {
        $this->apiKey = $apiKey;
        $this->idLang = (int)$id_lang;
    }

    /**
     * @return mixed - Возвращает массив доступных на сервере языков.
     */
    public function getLanguages(){
        return $this->execute('Languages', 'getAllLanguages');
    }

    /**
     * @param $id_group - id Группы
     * @param string $type - тип ценообразования: b2c - Цены для всех; b2b - цены для посредников; my - Свои цены (с учетом скидок, если есть);
     * @return mixed - Возвращает массив прайс-листа по id группы
     */
    public function getPriceByGroup($id_group, $type = 'b2c'){
        return $this->execute('Price', 'getPriceByGroup', ['id_group' => (int)$id_group, 'type' => (string)$type]);
    }

    /**
     * @return mixed - Возвращает массив Групп Продуктов
     */
    public function getAllGroups(){
        return $this->execute('GroupProduct', 'getAllGroup');
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

        //$url = 'http://api.wolf.ua/api/v1';
        $url = 'http://dev.api.wolf.ua/v1/';
        $data['dev'] = $this->dev;
        $data = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'APIKey : '.$this->apiKey));
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