<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\GoogleFileService;

class EmbedController extends Controller
{
    /**
     * Solução 3: Proxy Server para contornar CORS
     * Retorna o conteúdo HTML da URL via proxy
     */
    public function proxy(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        $url = $request->input('url');

        try {
            // Verifica se é arquivo do Google e converte
            if (GoogleFileService::isGoogleFile($url)) {
                $embedUrl = GoogleFileService::convertToEmbedUrl($url);
                return response()->json([
                    'success' => true,
                    'embedUrl' => $embedUrl,
                    'type' => 'google',
                    'fileInfo' => GoogleFileService::getFileInfo($url),
                ]);
            }

            // Para outras URLs, retorna a URL original
            return response()->json([
                'success' => true,
                'embedUrl' => $url,
                'type' => 'web',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao processar URL: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Solução 4: Fetch de metadados para preview
     * Retorna metadados da página (Open Graph, etc)
     */
    public function fetchMetadata(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        $url = $request->input('url');

        try {
            // Verifica se é arquivo do Google
            if (GoogleFileService::isGoogleFile($url)) {
                $fileInfo = GoogleFileService::getFileInfo($url);
                return response()->json([
                    'success' => true,
                    'metadata' => [
                        'title' => $fileInfo['name'],
                        'description' => 'Arquivo do Google - ' . $fileInfo['name'],
                        'type' => $fileInfo['type'],
                        'icon' => $fileInfo['icon'],
                        'embedUrl' => $fileInfo['embedUrl'],
                    ],
                ]);
            }

            // Para outras URLs, tenta buscar metadados
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ])
                ->get($url);

            if (!$response->successful()) {
                throw new \Exception('Não foi possível acessar a URL');
            }

            $html = $response->body();
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
            libxml_clear_errors();

            $xpath = new \DOMXPath($dom);

            $metadata = [
                'title' => $this->getMetaContent($xpath, ['og:title', 'twitter:title', 'title']),
                'description' => $this->getMetaContent($xpath, ['og:description', 'twitter:description', 'description']),
                'image' => $this->getMetaContent($xpath, ['og:image', 'twitter:image']),
                'url' => $this->getMetaContent($xpath, ['og:url']) ?: $url,
            ];

            // Se não encontrou título, tenta pegar do <title>
            if (!$metadata['title']) {
                $titleNode = $dom->getElementsByTagName('title')->item(0);
                if ($titleNode) {
                    $metadata['title'] = trim($titleNode->textContent);
                }
            }

            return response()->json([
                'success' => true,
                'metadata' => array_filter($metadata),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao buscar metadados: ' . $e->getMessage(),
            ], 400);
        }
    }

    private function getMetaContent($xpath, $properties)
    {
        foreach ($properties as $property) {
            $query = "//meta[@property='{$property}']/@content";
            $nodes = $xpath->query($query);
            if ($nodes->length > 0) {
                return $nodes->item(0)->nodeValue;
            }

            $query = "//meta[@name='{$property}']/@content";
            $nodes = $xpath->query($query);
            if ($nodes->length > 0) {
                return $nodes->item(0)->nodeValue;
            }
        }
        return null;
    }

    /**
     * Solução 5: Validação e conversão de URL
     * Retorna URL validada e convertida para embed
     */
    public function validateUrl(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        $url = $request->input('url');

        try {
            $result = [
                'success' => true,
                'originalUrl' => $url,
                'embedUrl' => $url,
                'type' => 'web',
                'fileInfo' => null,
            ];

            // Verifica se é arquivo do Google
            if (GoogleFileService::isGoogleFile($url)) {
                $fileInfo = GoogleFileService::getFileInfo($url);
                $result['embedUrl'] = GoogleFileService::convertToEmbedUrl($url);
                $result['type'] = 'google';
                $result['fileInfo'] = $fileInfo;
            }

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao validar URL: ' . $e->getMessage(),
            ], 400);
        }
    }
}




