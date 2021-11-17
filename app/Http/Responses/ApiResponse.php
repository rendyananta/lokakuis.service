<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ApiResponse implements Responsable 
{
    private array $content;

    private bool $wrap;

    private int $httpCode;

    public function __construct($content, $httpCode = 200)
    {
        $this->content = $this->sanitizeToArray($content);
        $this->httpCode = $httpCode;
        $this->wrap = true;
    }

    public function toResponse($request)
    {
        $body = !$this->wrap ? $this->content : [
            'data' => $this->content,
        ];

        return response()->json($body, $this->httpCode);
    }

    private function sanitizeToArray($content): array 
    {
        /* $request Request **/
        $request = app(Request::class);

        if ($content instanceof JsonResource or $content instanceof ResourceCollection) {
            $this->wrap = false;

            return $content->toArray($request);
        }

        if (is_string($content)) {
            $this->wrap = false;

            return ["messages" => $content];
        }

        if ($content instanceof Arrayable) {
            return $content->toArray();
        }

        if (is_array($content)) {
            return $content;
        }

        return [$content];
    }
}