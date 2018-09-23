<?php

namespace diag\cc;

class Pregunta extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id_pregunta", type="integer", length=11, nullable=false)
     */
    protected $id_pregunta;

    /**
     *
     * @var string
     * @Column(column="tipo", type="string", length=1, nullable=false)
     */
    protected $tipo;

    /**
     *
     * @var string
     * @Column(column="txt_pregunta", type="string", length=100, nullable=false)
     */
    protected $txt_pregunta;

    /**
     *
     * @var integer
     * @Column(column="id_diagnostico", type="integer", length=11, nullable=false)
     */
    protected $id_diagnostico;

    /**
     *
     * @var integer
     * @Column(column="id_categoria", type="integer", length=11, nullable=false)
     */
    protected $id_categoria;

    /**
     * Method to set the value of field id_pregunta
     *
     * @param integer $id_pregunta
     * @return $this
     */
    public function setIdPregunta($id_pregunta)
    {
        $this->id_pregunta = $id_pregunta;

        return $this;
    }

    /**
     * Method to set the value of field tipo
     *
     * @param string $tipo
     * @return $this
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Method to set the value of field txt_pregunta
     *
     * @param string $txt_pregunta
     * @return $this
     */
    public function setTxtPregunta($txt_pregunta)
    {
        $this->txt_pregunta = $txt_pregunta;

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
     * Method to set the value of field id_categoria
     *
     * @param integer $id_categoria
     * @return $this
     */
    public function setIdCategoria($id_categoria)
    {
        $this->id_categoria = $id_categoria;

        return $this;
    }

    /**
     * Returns the value of field id_pregunta
     *
     * @return integer
     */
    public function getIdPregunta()
    {
        return $this->id_pregunta;
    }

    /**
     * Returns the value of field tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Returns the value of field txt_pregunta
     *
     * @return string
     */
    public function getTxtPregunta()
    {
        return $this->txt_pregunta;
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
     * Returns the value of field id_categoria
     *
     * @return integer
     */
    public function getIdCategoria()
    {
        return $this->id_categoria;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_diagnostico");
        $this->setSource("pregunta");
        $this->hasMany('id_pregunta', 'diag\cc\OpcRespuesta', 'id_pregunta', ['alias' => 'OpcRespuesta']);
        $this->belongsTo('id_categoria', 'diag\cc\Categoria', 'id_categoria', ['alias' => 'Categoria']);
        $this->belongsTo('id_diagnostico', 'diag\cc\Diagnostico', 'id_diagnostico', ['alias' => 'Diagnostico']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'pregunta';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Pregunta[]|Pregunta|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Pregunta|\Phalcon\Mvc\Model\ResultInterface
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
            'id_pregunta' => 'id_pregunta',
            'tipo' => 'tipo',
            'txt_pregunta' => 'txt_pregunta',
            'id_diagnostico' => 'id_diagnostico',
            'id_categoria' => 'id_categoria'
        ];
    }

}
