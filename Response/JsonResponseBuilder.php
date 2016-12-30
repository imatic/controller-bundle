<?php

namespace Imatic\Bundle\ControllerBundle\Response;

use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class JsonResponseBuilder
{
    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var CommandResultInterface
     */
    private $commandResult;

    /**
     * @var mixed|\Closure
     */
    private $data;

    /**
     * @var null
     */
    private $status;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var bool
     */
    private $debug;

    public function __construct($data = null, $status = null, array $headers = [])
    {
        $this->data = $data;
        $this->status = $status;
        $this->headers = $headers;
        $this->debug = false;
    }

    public static function createFromHandle(array $handle)
    {
        return static::create()
            ->setForm($handle['form'])
            ->setCommandResult(isset($handle['result']) ? $handle['result'] : null);
    }

    public static function create($data = null, $status = null, array $headers = [])
    {
        return new static($data, $status, $headers);
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function setForm(FormInterface $form = null)
    {
        $this->form = $form;

        return $this;
    }

    public function setCommandResult(CommandResultInterface $commandResult = null)
    {
        $this->commandResult = $commandResult;

        return $this;
    }

    public function setDebug($debug)
    {
        $this->debug = (bool) $debug;

        return $this;
    }

    /**
     * @return JsonResponse
     */
    public function getResponse()
    {
        $this->update();

        $response = new JsonResponse($this->data, $this->status, $this->headers);

        return $response;
    }

    private function update()
    {
        if ($this->form && !$this->form->isSubmitted()) {
            $this->status = 500;
            $this->data = $this->getErrorData('Form is not submitted');
        } elseif ($this->form && !$this->form->isValid()) {
            $this->status = 400;
            $this->data = $this->getErrorData('Form is not valid');

            $errors = [];
            foreach ($this->form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }

            if ($errors) {
                $this->data['messages'] = $errors;
            }
        } elseif ($this->commandResult && !$this->commandResult->isSuccessful()) {
            if ($this->commandResult->hasException() && $this->debug) {
                $message = $this->commandResult->getException()->getMessage();
            } else {
                $message = 'Error';
            }
            $this->status = 500;
            $this->data = $this->getErrorData($message);
        } else {
            $this->status = 200;
            if ($this->data instanceof \Closure) {
                $closure = $this->data;
                $this->data = $closure($this->commandResult, $this->form);
            }
        }
    }

    private function getErrorData($message)
    {
        return ['error' => ['code' => $this->status, 'message' => $message]];
    }
}
