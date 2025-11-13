<?php

namespace App\Services;

class GoogleFileService
{
    /**
     * Detecta se a URL é do Google (Drive, Docs, Sheets, PDF)
     */
    public static function isGoogleFile($url)
    {
        return preg_match('/drive\.google\.com|docs\.google\.com/', $url) ||
               preg_match('/\.pdf(\?.*)?$/', $url);
    }

    /**
     * Detecta o tipo de arquivo Google
     */
    public static function detectGoogleFileType($url)
    {
        // Google Docs
        if (preg_match('/docs\.google\.com\/document\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return [
                'type' => 'doc',
                'id' => $matches[1],
                'embedUrl' => 'https://docs.google.com/document/d/' . $matches[1] . '/preview',
            ];
        }

        // Google Sheets
        if (preg_match('/docs\.google\.com\/spreadsheets\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return [
                'type' => 'sheet',
                'id' => $matches[1],
                'embedUrl' => 'https://docs.google.com/spreadsheets/d/' . $matches[1] . '/preview',
            ];
        }

        // Google Slides
        if (preg_match('/docs\.google\.com\/presentation\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return [
                'type' => 'slides',
                'id' => $matches[1],
                'embedUrl' => 'https://docs.google.com/presentation/d/' . $matches[1] . '/preview',
            ];
        }

        // Google Drive File (genérico)
        if (preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return [
                'type' => 'drive',
                'id' => $matches[1],
                'embedUrl' => 'https://drive.google.com/file/d/' . $matches[1] . '/preview',
            ];
        }

        // Google Drive com open?id=
        if (preg_match('/drive\.google\.com\/open\?id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return [
                'type' => 'drive',
                'id' => $matches[1],
                'embedUrl' => 'https://drive.google.com/file/d/' . $matches[1] . '/preview',
            ];
        }

        // PDF direto
        if (preg_match('/\.pdf(\?.*)?$/i', $url)) {
            return [
                'type' => 'pdf',
                'id' => null,
                'embedUrl' => 'https://docs.google.com/viewer?url=' . urlencode($url) . '&embedded=true',
            ];
        }

        return null;
    }

    /**
     * Converte URL do Google para formato embed
     */
    public static function convertToEmbedUrl($url)
    {
        $fileInfo = self::detectGoogleFileType($url);
        
        if ($fileInfo) {
            return $fileInfo['embedUrl'];
        }

        return $url;
    }

    /**
     * Obtém informações do arquivo para preview
     */
    public static function getFileInfo($url)
    {
        $fileInfo = self::detectGoogleFileType($url);
        
        if (!$fileInfo) {
            return null;
        }

        $types = [
            'doc' => ['name' => 'Google Docs', 'icon' => '📄'],
            'sheet' => ['name' => 'Google Sheets', 'icon' => '📊'],
            'slides' => ['name' => 'Google Slides', 'icon' => '📽️'],
            'drive' => ['name' => 'Google Drive', 'icon' => '📁'],
            'pdf' => ['name' => 'PDF', 'icon' => '📕'],
        ];

        return [
            'type' => $fileInfo['type'],
            'name' => $types[$fileInfo['type']]['name'] ?? 'Arquivo',
            'icon' => $types[$fileInfo['type']]['icon'] ?? '📄',
            'embedUrl' => $fileInfo['embedUrl'],
        ];
    }
}




