# 🎬 Series Organizer

Um sistema completo para organizar e gerenciar suas séries favoritas, desenvolvido com Laravel 11.

## ✨ Funcionalidades

- 📺 Cadastro e gerenciamento de séries
- 📅 Controle de temporadas e episódios
- 🖼️ Upload e gerenciamento de imagens
- 📊 Dashboard com visão geral
- 🔍 Busca e filtros
- ⭐ Sistema de avaliações
- 👤 Autenticação de usuários
- 📱 Interface responsiva

## 🚀 Tecnologias

- **Backend**: Laravel 11, PHP 8.2+
- **Frontend**: Tailwind CSS, Alpine.js
- **Banco de Dados**: MySQL/PostgreSQL/SQLite
- **Upload de Arquivos**: Sistema nativo Laravel
- **Autenticação**: Laravel Breeze

## 📋 Pré-requisitos

- PHP 8.2 ou superior
- Composer
- Node.js e NPM
- MySQL/PostgreSQL ou SQLite

## 🛠️ Instalação

1. **Clone o repositório**
   ```bash
   git clone https://github.com/seu-usuario/series-organizer.git
   cd series-organizer
   ```

2. **Instale as dependências PHP**
   ```bash
   composer install
   ```

3. **Configure o ambiente**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure o banco de dados**
   ```bash
   # Edite o arquivo .env com suas configurações de banco
   php artisan migrate
   php artisan db:seed
   ```

5. **Instale as dependências Node.js**
   ```bash
   npm install
   npm run build
   ```

6. **Configure o storage**
   ```bash
   php artisan storage:link
   ```

7. **Inicie o servidor**
   ```bash
   php artisan serve
   ```

## 📁 Estrutura do Projeto

```
series-organizer/
├── app/
│   ├── Http/Controllers/    # Controladores
│   ├── Models/             # Modelos Eloquent
│   └── Services/           # Serviços de negócio
├── database/
│   ├── migrations/         # Migrações do banco
│   ├── seeders/           # Seeders
│   └── factories/         # Factories para testes
├── resources/
│   ├── views/             # Views Blade
│   ├── css/               # Estilos CSS
│   └── js/                # JavaScript
└── routes/
    └── web.php            # Rotas web
```

## 🎯 Funcionalidades Principais

### Gerenciamento de Séries
- Criar, editar e excluir séries
- Upload de imagens de capa
- Informações detalhadas (título, descrição, data de lançamento)

### Sistema de Temporadas
- Organizar episódios por temporadas
- Controle de ordem das temporadas
- Visualização hierárquica

### Dashboard
- Visão geral de todas as séries
- Estatísticas de assistidas
- Interface moderna e responsiva

## 🔧 Configuração

### Variáveis de Ambiente

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=series_organizer
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
```

### Permissões de Arquivo

```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

## 🧪 Testes

```bash
# Executar todos os testes
php artisan test

# Executar testes específicos
php artisan test --filter=SeriesTest
```

## 📝 API

O projeto inclui uma API REST para integração com outros sistemas:

- `GET /api/series` - Listar séries
- `POST /api/series` - Criar série
- `GET /api/series/{id}` - Obter série específica
- `PUT /api/series/{id}` - Atualizar série
- `DELETE /api/series/{id}` - Excluir série

## 🤝 Contribuindo

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 👨‍💻 Autor

**Seu Nome**
- GitHub: [@seu-usuario](https://github.com/seu-usuario)

## 🙏 Agradecimentos

- [Laravel](https://laravel.com) - Framework PHP
- [Tailwind CSS](https://tailwindcss.com) - Framework CSS
- [Alpine.js](https://alpinejs.dev) - Framework JavaScript

---

⭐ Se este projeto te ajudou, considere dar uma estrela!
