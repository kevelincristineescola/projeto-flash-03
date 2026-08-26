# Descubra seu Signo

Página web em PHP + SQLite + Bootstrap que descobre o signo do zodíaco a partir
da data de nascimento informada pelo usuário.

## Estrutura do projeto

```
signo-app/
├── index.php          → página inicial com o formulário
├── resultado.php       → calcula o signo e mostra as informações
├── config.php          → conexão PDO com o banco SQLite
├── criar_banco.php     → cria e popula o banco (rodar 1x)
├── banco/               → onde o arquivo signos.db será criado
└── assets/
    └── css/
        └── style.css   → estilização própria (usada junto com Bootstrap)
```

## Como rodar no XAMPP

1. **Copie a pasta `signo-app`** inteira para dentro de `htdocs`:
   - Windows: `C:\xampp\htdocs\signo-app`
   - Linux: `/opt/lampp/htdocs/signo-app`
   - macOS: `/Applications/XAMPP/htdocs/signo-app`

2. **Habilite as extensões do SQLite no PHP** (geralmente já vêm ativadas no
   XAMPP). Abra `php.ini` (pelo painel do XAMPP: *Config → PHP (php.ini)*) e
   confirme que estas linhas **não** têm `;` na frente:
   ```
   extension=pdo_sqlite
   extension=sqlite3
   ```
   Se precisar alterar, reinicie o Apache depois.

3. **Inicie o Apache** pelo painel de controle do XAMPP.

4. **Crie o banco de dados**: abra no navegador
   ```
   http://localhost/signo-app/criar_banco.php
   ```
   Isso cria a pasta `banco/`, o arquivo `signos.db` e cadastra os 12 signos.
   Você só precisa fazer isso **uma vez** (rodar de novo apenas recria os dados).

5. **Acesse a página inicial**:
   ```
   http://localhost/signo-app/index.php
   ```
   Preencha a data de nascimento e clique em "Ver meu signo".

## Como funciona

- `index.php` mostra um formulário Bootstrap com um campo `<input type="date">`
  que envia a data via `POST` para `resultado.php`.
- `resultado.php` calcula o dia e o mês da data recebida, descobre a qual
  signo eles pertencem (função `determinarSigno()`) e faz uma consulta
  `SELECT` no SQLite para trazer nome, elemento, planeta regente, pedra,
  características, pontos fortes/fracos e compatibilidade.
- Os dados voltam formatados em um cartão, junto com uma roda do zodíaco em
  SVG que destaca em dourado a posição do signo encontrado.
- Todo o layout usa Bootstrap 5 (grid, formulário, cartão) combinado com a
  folha de estilo própria `assets/css/style.css`, que define a paleta,
  tipografia e o fundo estrelado.

## Personalizações fáceis

- **Trocar as cores**: edite as variáveis no topo de `assets/css/style.css`
  (bloco `:root`).
- **Editar os textos de cada signo**: altere os valores no array `$signos`
  dentro de `criar_banco.php` e rode o arquivo novamente no navegador.
- **Trocar de SQLite para MySQL**: troque a conexão em `config.php` por
  `new PDO('mysql:host=localhost;dbname=signos', $usuario, $senha)` e ajuste
  a sintaxe de criação da tabela em `criar_banco.php` (o SQL é quase idêntico).
