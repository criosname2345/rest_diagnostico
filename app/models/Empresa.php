<?php

namespace diag\cc;

class Empresa extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id_empresa", type="integer", length=11, nullable=false)
     */
    protected $id_empresa;

    /**
     *
     * @var string
     * @Column(column="razon_social", type="string", length=80, nullable=false)
     */
    protected $razon_social;

    /**
     *
     * @var string
     * @Column(column="nit", type="string", length=12, nullable=false)
     */
    protected $nit;

    /**
     *
     * @var string
     * @Column(column="afiliacion", type="string", length=20, nullable=true)
     */
    protected $afiliacion;

    /**
     *
     * @var string
     * @Column(column="web", type="string", length=70, nullable=true)
     */
    protected $web;

    /**
     *
     * @var string
     * @Column(column="repr_legal", type="string", length=80, nullable=false)
     */
    protected $repr_legal;

    /**
     *
     * @var string
     * @Column(column="ger_general", type="string", length=80, nullable=true)
     */
    protected $ger_general;

    /**
     *
     * @var string
     * @Column(column="direccion", type="string", length=100, nullable=false)
     */
    protected $direccion;

    /**
     *
     * @var string
     * @Column(column="constitucion", type="string", nullable=false)
     */
    protected $constitucion;

    /**
     *
     * @var string
     * @Column(column="ccit", type="string", length=20, nullable=true)
     */
    protected $ccit;

    /**
     *
     * @var integer
     * @Column(column="es_cc", type="integer", length=1, nullable=true)
     */
    protected $es_cc;

    /**
     *
     * @var integer
     * @Column(column="camara_comercio", type="integer", length=11, nullable=true)
     */
    protected $camara_comercio;

    /**
     *
     * @var integer
     * @Column(column="id_diagnostico", type="integer", length=11, nullable=true)
     */
    protected $id_diagnostico;

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
     * Method to set the value of field razon_social
     *
     * @param string $razon_social
     * @return $this
     */
    public function setRazonSocial($razon_social)
    {
        $this->razon_social = $razon_social;

        return $this;
    }

    /**
     * Method to set the value of field nit
     *
     * @param string $nit
     * @return $this
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Method to set the value of field afiliacion
     *
     * @param string $afiliacion
     * @return $this
     */
    public function setAfiliacion($afiliacion)
    {
        $this->afiliacion = $afiliacion;

        return $this;
    }

    /**
     * Method to set the value of field web
     *
     * @param string $web
     * @return $this
     */
    public function setWeb($web)
    {
        $this->web = $web;

        return $this;
    }

    /**
     * Method to set the value of field repr_legal
     *
     * @param string $repr_legal
     * @return $this
     */
    public function setReprLegal($repr_legal)
    {
        $this->repr_legal = $repr_legal;

        return $this;
    }

    /**
     * Method to set the value of field ger_general
     *
     * @param string $ger_general
     * @return $this
     */
    public function setGerGeneral($ger_general)
    {
        $this->ger_general = $ger_general;

        return $this;
    }

    /**
     * Method to set the value of field direccion
     *
     * @param string $direccion
     * @return $this
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Method to set the value of field constitucion
     *
     * @param string $constitucion
     * @return $this
     */
    public function setConstitucion($constitucion)
    {
        $this->constitucion = $constitucion;

        return $this;
    }

    /**
     * Method to set the value of field ccit
     *
     * @param string $ccit
     * @return $this
     */
    public function setCcit($ccit)
    {
        $this->ccit = $ccit;

        return $this;
    }

    /**
     * Method to set the value of field es_cc
     *
     * @param integer $es_cc
     * @return $this
     */
    public function setEsCc($es_cc)
    {
        $this->es_cc = $es_cc;

        return $this;
    }

    /**
     * Method to set the value of field camara_comercio
     *
     * @param integer $camara_comercio
     * @return $this
     */
    public function setCamaraComercio($camara_comercio)
    {
        $this->camara_comercio = $camara_comercio;

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
     * Returns the value of field id_empresa
     *
     * @return integer
     */
    public function getIdEmpresa()
    {
        return $this->id_empresa;
    }

    /**
     * Returns the value of field razon_social
     *
     * @return string
     */
    public function getRazonSocial()
    {
        return $this->razon_social;
    }

    /**
     * Returns the value of field nit
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Returns the value of field afiliacion
     *
     * @return string
     */
    public function getAfiliacion()
    {
        return $this->afiliacion;
    }

    /**
     * Returns the value of field web
     *
     * @return string
     */
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * Returns the value of field repr_legal
     *
     * @return string
     */
    public function getReprLegal()
    {
        return $this->repr_legal;
    }

    /**
     * Returns the value of field ger_general
     *
     * @return string
     */
    public function getGerGeneral()
    {
        return $this->ger_general;
    }

    /**
     * Returns the value of field direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Returns the value of field constitucion
     *
     * @return string
     */
    public function getConstitucion()
    {
        return $this->constitucion;
    }

    /**
     * Returns the value of field ccit
     *
     * @return string
     */
    public function getCcit()
    {
        return $this->ccit;
    }

    /**
     * Returns the value of field es_cc
     *
     * @return integer
     */
    public function getEsCc()
    {
        return $this->es_cc;
    }

    /**
     * Returns the value of field camara_comercio
     *
     * @return integer
     */
    public function getCamaraComercio()
    {
        return $this->camara_comercio;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_diagnostico");
        $this->setSource("empresa");
        $this->hasMany('id_empresa', 'diag\cc\Contacto', 'id_empresa', ['alias' => 'Contacto']);
        $this->hasMany('id_empresa', 'diag\cc\Diagnostico', 'id_empresa', ['alias' => 'Diagnostico']);
        $this->hasMany('id_empresa', 'diag\cc\Empresa', 'camara_comercio', ['alias' => 'Empresa']);
        $this->hasMany('id_empresa', 'diag\cc\Intento', 'id_empresa', ['alias' => 'Intento']);
        $this->hasMany('id_empresa', 'diag\cc\Visita', 'id_empresa', ['alias' => 'Visita']);
        $this->belongsTo('camara_comercio', 'diag\cc\Empresa', 'id_empresa', ['alias' => 'Empresa']);
        $this->belongsTo('id_diagnostico', 'diag\cc\Diagnostico', 'id_diagnostico', ['alias' => 'Diagnostico']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'empresa';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Empresa[]|Empresa|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Empresa|\Phalcon\Mvc\Model\ResultInterface
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
            'id_empresa' => 'id_empresa',
            'razon_social' => 'razon_social',
            'nit' => 'nit',
            'afiliacion' => 'afiliacion',
            'web' => 'web',
            'repr_legal' => 'repr_legal',
            'ger_general' => 'ger_general',
            'direccion' => 'direccion',
            'constitucion' => 'constitucion',
            'ccit' => 'ccit',
            'es_cc' => 'es_cc',
            'camara_comercio' => 'camara_comercio',
            'id_diagnostico' => 'id_diagnostico'
        ];
    }

}
