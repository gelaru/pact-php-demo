<?php

namespace CompaniesConsumer;

/**
 * @property int id
 * @property string name
 * @property int industry_id
 * @property string date_created
 * @property string date_updated
 * @property array location
 * @property array profile
 * @property array review_count
 * @property string hash
 * @property boolean deleted
 */
class Company
{
    /**
     * Alligator constructor.
     * @param array $data
     */
    public function __construct($data)
    {
        foreach($data as $field => $value){
            $this->$field = $value;
        }
    }
}