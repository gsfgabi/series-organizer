<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavedLink;
use App\Services\GoogleFileService;

class SavedLinkController extends Controller
{
    /**
     * Lista todos os links salvos
     */
    public function index()
    {
        $links = SavedLink::orderBy('order')->orderBy('created_at', 'desc')->get();
        return response()->json($links);
    }

    /**
     * Salva um novo link
     */
    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'title' => 'nullable|string|max:255',
        ]);

        // Detecta tipo de arquivo para definir ícone
        $icon = '🔗';
        $fileInfo = null;
        
        if (GoogleFileService::isGoogleFile($request->url)) {
            $fileInfo = GoogleFileService::getFileInfo($request->url);
            $icon = $fileInfo['icon'] ?? '🔗';
        }

        $link = SavedLink::create([
            'url' => $request->url,
            'title' => $request->title ?? $this->generateTitle($request->url, $fileInfo),
            'icon' => $icon,
            'order' => SavedLink::max('order') + 1,
        ]);

        return response()->json([
            'success' => true,
            'link' => $link,
        ]);
    }

    /**
     * Remove um link
     */
    public function destroy($id)
    {
        $link = SavedLink::findOrFail($id);
        $link->delete();

        return response()->json([
            'success' => true,
            'message' => 'Link removido com sucesso',
        ]);
    }

    /**
     * Obtém informações de um link para exibir
     */
    public function show($id)
    {
        $link = SavedLink::findOrFail($id);
        
        // Processa URL para embed
        $embedUrl = $link->url;
        $fileInfo = null;
        
        if (GoogleFileService::isGoogleFile($link->url)) {
            $fileInfo = GoogleFileService::getFileInfo($link->url);
            $embedUrl = GoogleFileService::convertToEmbedUrl($link->url);
        }

        return response()->json([
            'success' => true,
            'url' => $link->url,
            'embedUrl' => $embedUrl,
            'fileInfo' => $fileInfo,
        ]);
    }

    /**
     * Gera título automático baseado na URL
     */
    private function generateTitle($url, $fileInfo = null)
    {
        if ($fileInfo) {
            return $fileInfo['name'] ?? 'Link Salvo';
        }

        // Tenta extrair título da URL
        $parsed = parse_url($url);
        $host = $parsed['host'] ?? 'Link';
        
        // Remove www. e .com
        $title = str_replace(['www.', '.com', '.br'], '', $host);
        $title = ucfirst($title);

        return $title;
    }
}
