<?php

namespace diag\cc;

class Intento extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id_intento", type="integer", length=11, nullable=false)
     */
    protected $id_intento;

    /**
     *
     * @var integer
     * @Column(column="id_empresa", type="integer", length=11, nullable=false)
     */
    protected $id_empresa;

    /**
     *
     * @var integer
     * @Column(column="id_diagnostico", type="integer", length=11, nullable=false)
     */
    protected $id_diagnostico;

    /**
     *
     * @var string
     * @Column(column="fecha", type="string", nullable=false)
     */
    protected $fecha;

    /**
     *
     * @var integer
     * @Column(column="resultado", type="integer", length=11, nullable=false)
     */
    protected $resultado;

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
     * Method to set the value of field id_empresa
     *
     * @param integer $id_empresa
     * @return $this
     */
    public function setIdEmpresa($id_empresa)
    {
        $this->id_empresa = $id_empresa;

        return $this;
    }

    /**
     * Method to set the value of field id_diagnostico
     *
     * @param integer $id_diagnostico
     * @return $this
     */
    public function setIdDiagnostico($id_diagnostico)
    {
        $this->id_diagnostico = $id_diagnostico;

        return $this;
    }

    /**
     * Method to set the value of field fecha
     *
     * @param string $fecha
     * @return $this
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Method to set the value of field resultado
     *
     * @param integer $resultado
     * @return $this
     */
    public function setResultado($resultado)
    {
        $this->resultado = $resultado;

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
     * Returns the value of field id_empresa
     *
     * @return integer
     */
    public function getIdEmpresa()
    {
        return $this->id_empresa;
    }

    /**
     * Returns the value of field id_diagnostico
     *
     * @return integer
     */
    public function getIdDiagnostico()
    {
        return $this->id_diagnostico;
    }

    /**
     * Returns the value of field fecha
     *
     * @return string
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Returns the value of field resultado
     *
     * @return integer
     */
    public function getResultado()
    {
        return $this->resultado;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_diagnostico");
        $this->setSource("intento");
        $this->hasMany('id_intento', 'diag\cc\IntentoRespuesta', 'id_intento', ['alias' => 'IntentoRespuesta']);
        $this->belongsTo('id_diagnostico', 'diag\cc\Diagnostico', 'id_diagnostico', ['alias' => 'Diagnostico']);
        $this->belongsTo('id_empresa', 'diag\cc\Empresa', 'id_empresa', ['alias' => 'Empresa']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'intento';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Intento[]|Intento|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Intento|\Phalcon\Mvc\Model\ResultInterface
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
            'id_empresa' => 'id_empresa',
            'id_diagnostico' => 'id_diagnostico',
            'fecha' => 'fecha',
            'resultado' => 'resultado'
        ];
    }

}
