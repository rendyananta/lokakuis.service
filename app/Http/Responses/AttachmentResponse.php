<?php
namespace App\Http\Responses;

use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentResponse
{
    /**
     * Http Request instance.
     *
     * @var Request
     */
    public $request;

    /**
     * Filesystem singleton instance.
     *
     * @var Factory
     */
    public $filesystem;

    /**
     * Response singleton instance.
     *
     * @var ResponseFactory;
     */
    public $response;

    /**
     * AttachmentResponse constructor.
     * @param Request $request
     * @param Factory $filesystem
     * @param ResponseFactory $response
     */
    public function __construct(Request $request, Factory $filesystem, ResponseFactory $response)
    {
        $this->request = $request;
        $this->filesystem = $filesystem;
        $this->response = $response;
    }

    /**
     * Stream or download response. Give stream response if stream request query provided.
     *
     * @param Attachment $attachment
     * @return \Illuminate\Http\Response|mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function streamOrDownload(string $disk, string $path)
    {
        return $this->request->has('stream') && (bool) $this->request->input('stream') === true
            ? $this->stream($disk, $path)
            : $this->download($disk, $path);
    }

    /**
     * Find attachment file in filesystem.
     *
     * @param string $disk
     * @param string $path
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getAttachmentFile(string $disk, string $path)
    {
        return $this->filesystem->disk($disk)->get($path);
    }


    /**
     * Make stream response file.
     *
     * @param string $disk
     * @param string $path
     * @return \Illuminate\Http\Response|mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function stream(string $disk, string $path)
    {
        $file = $this->getAttachmentFile($disk, $path);

        $response = $this->response->make($file, 200);
        $response->header('Content-Type', Storage::mimeType($path));

        return $response;
    }

    /**
     * Make download response.
     *
     * @param string $disk
     * @param string $path
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(string $disk, string $path)
    {
        $file = $this->getAttachmentFile($disk, $path);
        return $this->response->download($path, basename($path));
    }
}