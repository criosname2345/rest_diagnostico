<?php

namespace diag\cc;

class IntentoRespuesta extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Column(column="id_intento", type="integer", length=11, nullable=false)
     */
    protected $id_intento;

    /**
     *
     * @var integer
     * @Primary
     * @Column(column="id_respuesta", type="integer", length=11, nullable=false)
     */
    protected $id_respuesta;

    /**
     * Method to set the value of field id_intento
     *
     * @param integer $id_intento
     * @return $this
     */
    public function setIdIntento($id_intento)
    {
        $this->id_intento = $id_intento;

        return $this;
    }

    /**
     * Method to set the value of field id_respuesta
     *
     * @param integer $id_respuesta
     * @return $this
     */
    public function setIdRespuesta($id_respuesta)
    {
        $this->id_respuesta = $id_respuesta;

        return $this;
    }

    /**
     * Returns the value of field id_intento
     *
     * @return integer
     */
    public function getIdIntento()
    {
        return $this->id_intento;
    }

    /**
     * Returns the value of field id_respuesta
     *
     * @return integer
     */
    public function getIdRespuesta()
    {
        return $this->id_respuesta;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_diagnostico");
        $this->setSource("intento_respuesta");
        $this->belongsTo('id_intento', 'diag\cc\Intento', 'id_intento', ['alias' => 'Intento']);
        $this->belongsTo('id_respuesta', 'diag\cc\OpcRespuesta', 'id_respuesta', ['alias' => 'OpcRespuesta']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'intento_respuesta';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return IntentoRespuesta[]|IntentoRespuesta|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return IntentoRespuesta|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return [
            'id_intento' => 'id_intento',
            'id_respuesta' => 'id_respuesta'
        ];
    }

}
