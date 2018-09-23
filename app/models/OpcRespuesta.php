<?php

namespace diag\cc;

class OpcRespuesta extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id_respuesta", type="integer", length=11, nullable=false)
     */
    protected $id_respuesta;

    /**
     *
     * @var integer
     * @Column(column="puntaje", type="integer", length=11, nullable=false)
     */
    protected $puntaje;

    /**
     *
     * @var string
     * @Column(column="texto", type="string", length=100, nullable=true)
     */
    protected $texto;

    /**
     *
     * @var integer
     * @Column(column="id_pregunta", type="integer", length=11, nullable=false)
     */
    protected $id_pregunta;

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
     * Method to set the value of field puntaje
     *
     * @param integer $puntaje
     * @return $this
     */
    public function setPuntaje($puntaje)
    {
        $this->puntaje = $puntaje;

        return $this;
    }

    /**
     * Method to set the value of field texto
     *
     * @param string $texto
     * @return $this
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;

        return $this;
    }

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
     * Returns the value of field id_respuesta
     *
     * @return integer
     */
    public function getIdRespuesta()
    {
        return $this->id_respuesta;
    }

    /**
     * Returns the value of field puntaje
     *
     * @return integer
     */
    public function getPuntaje()
    {
        return $this->puntaje;
    }

    /**
     * Returns the value of field texto
     *
     * @return string
     */
    public function getTexto()
    {
        return $this->texto;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_diagnostico");
        $this->setSource("opc_respuesta");
        $this->hasMany('id_respuesta', 'diag\cc\IntentoRespuesta', 'id_respuesta', ['alias' => 'IntentoRespuesta']);
        $this->belongsTo('id_pregunta', 'diag\cc\Pregunta', 'id_pregunta', ['alias' => 'Pregunta']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'opc_respuesta';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return OpcRespuesta[]|OpcRespuesta|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return OpcRespuesta|\Phalcon\Mvc\Model\ResultInterface
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
            'id_respuesta' => 'id_respuesta',
            'puntaje' => 'puntaje',
            'texto' => 'texto',
            'id_pregunta' => 'id_pregunta'
        ];
    }

}
