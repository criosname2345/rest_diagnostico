<?php

namespace diag\cc;

class Diagnostico extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id_diagnostico", type="integer", length=11, nullable=false)
     */
    protected $id_diagnostico;

    /**
     *
     * @var integer
     * @Column(column="total_preguntas", type="integer", length=11, nullable=false)
     */
    protected $total_preguntas;

    /**
     *
     * @var integer
     * @Column(column="id_empresa", type="integer", length=11, nullable=false)
     */
    protected $id_empresa;

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
     * Method to set the value of field total_preguntas
     *
     * @param integer $total_preguntas
     * @return $this
     */
    public function setTotalPreguntas($total_preguntas)
    {
        $this->total_preguntas = $total_preguntas;

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
     * Returns the value of field id_diagnostico
     *
     * @return integer
     */
    public function getIdDiagnostico()
    {
        return $this->id_diagnostico;
    }

    /**
     * Returns the value of field total_preguntas
     *
     * @return integer
     */
    public function getTotalPreguntas()
    {
        return $this->total_preguntas;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_diagnostico");
        $this->setSource("diagnostico");
        $this->hasMany('id_diagnostico', 'diag\cc\Empresa', 'id_diagnostico', ['alias' => 'Empresa']);
        $this->hasMany('id_diagnostico', 'diag\cc\Intento', 'id_diagnostico', ['alias' => 'Intento']);
        $this->hasMany('id_diagnostico', 'diag\cc\Pregunta', 'id_diagnostico', ['alias' => 'Pregunta']);
        $this->belongsTo('id_empresa', 'diag\cc\Empresa', 'id_empresa', ['alias' => 'Empresa']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'diagnostico';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Diagnostico[]|Diagnostico|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Diagnostico|\Phalcon\Mvc\Model\ResultInterface
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
            'id_diagnostico' => 'id_diagnostico',
            'total_preguntas' => 'total_preguntas',
            'id_empresa' => 'id_empresa'
        ];
    }

}
