<?php
namespace SM\DemoOrder\Api\Data;

interface CreateOrderResultInterface
{
    /**#@+
     * Constants defined for keys of  data array
     */
    const ERROR = 'error';

    const MESSAGE = 'message';

    const ATTRIBUTES = [
        self::ERROR,
        self::MESSAGE,
    ];


    /**
     * @return bool
     */
    public function getError();

    /**
     * @param bool $error
     * @return $this
     */
    public function setError($error);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message);
}
