<?php

namespace diag\cc;

class Link extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id_link", type="integer", length=20, nullable=false)
     */
    protected $id_link;

    /**
     *
     * @var string
     * @Column(column="url", type="string", length=50, nullable=false)
     */
    protected $url;

    /**
     *
     * @var integer
     * @Column(column="id_empresa", type="integer", length=11, nullable=false)
     */
    protected $id_empresa;

    /**
     *
     * @var string
     * @Column(column="fecha", type="string", nullable=false)
     */
    protected $fecha;

    /**
     *
     * @var integer
     * @Column(column="diligenciado", type="integer", length=1, nullable=true)
     */
    protected $diligenciado;

    /**
     * Method to set the value of field id_link
     *
     * @param integer $id_link
     * @return $this
     */
    public function setIdLink($id_link)
    {
        $this->id_link = $id_link;

        return $this;
    }

    /**
     * Method to set the value of field url
     *
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

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
     * Method to set the value of field diligenciado
     *
     * @param integer $diligenciado
     * @return $this
     */
    public function setDiligenciado($diligenciado)
    {
        $this->diligenciado = $diligenciado;

        return $this;
    }

    /**
     * Returns the value of field id_link
     *
     * @return integer
     */
    public function getIdLink()
    {
        return $this->id_link;
    }

    /**
     * Returns the value of field url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
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
     * Returns the value of field fecha
     *
     * @return string
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Returns the value of field diligenciado
     *
     * @return integer
     */
    public function getDiligenciado()
    {
        return $this->diligenciado;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_diagnostico");
        $this->setSource("link");
        $this->belongsTo('id_empresa', 'diag\cc\Empresa', 'id_empresa', ['alias' => 'Empresa']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'link';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Link[]|Link|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Link|\Phalcon\Mvc\Model\ResultInterface
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
            'id_link' => 'id_link',
            'url' => 'url',
            'id_empresa' => 'id_empresa',
            'fecha' => 'fecha',
            'diligenciado' => 'diligenciado'
        ];
    }

}
