<?php

declare(strict_types=1);

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpUnprocessableEntityException extends HttpSpecializedException
{
    /**
     * @var int
     */
    protected $code = 422;

    /**
     * @var string
     */
    protected $message = 'Unprocessable Entity.';

    protected string $title = '422 Unprocessable Entity';
    protected string $description = 'We are unable to process your request due to invalid data supplied.';
}