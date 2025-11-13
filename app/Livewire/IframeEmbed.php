<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\GoogleFileService;

class IframeEmbed extends Component
{
    public $url = '';
    public $embedUrl = '';
    public $isLoading = false;
    public $error = null;
    public $fileInfo = null;

    protected $rules = [
        'url' => 'required|url',
    ];

    protected $messages = [
        'url.required' => 'Por favor, insira uma URL válida.',
        'url.url' => 'A URL informada não é válida.',
    ];

    public function loadUrl()
    {
        $this->validate();
        $this->isLoading = true;
        $this->error = null;
        $this->fileInfo = null;
        $this->embedUrl = '';

        if (!filter_var($this->url, FILTER_VALIDATE_URL)) {
            $this->error = 'URL não válida.';
            $this->isLoading = false;
            return;
        }

        if (GoogleFileService::isGoogleFile($this->url)) {
            $this->fileInfo = GoogleFileService::getFileInfo($this->url);
            $this->embedUrl = GoogleFileService::convertToEmbedUrl($this->url);
        } else {
            $this->embedUrl = $this->url;
        }

        $this->isLoading = false;
    }

    public function updatedUrl()
    {
        // Limpa o embed quando a URL muda
        $this->embedUrl = '';
        $this->fileInfo = null;
        $this->error = null;
    }

    public function clear()
    {
        $this->url = '';
        $this->embedUrl = '';
        $this->error = null;
        $this->fileInfo = null;
        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.iframe-embed');
    }
}
