# 🗄️ Dicionário de Dados 
### *Sistema de Gestão AVCB - BRasas*

Este documento descreve detalhadamente a estrutura, os tipos de dados e os relacionamentos das tabelas do banco de dados `avcb`.


## 👤 1. Tabela: USUARIOS

> Centraliza as informações de todos os perfis (Administradores, Gestores e Clientes), com suporte a dados específicos para Pessoa Física e Jurídica.

| Campo | Tipo | PK/FK | Nulo | Descrição |
| :--- | :--- | :---: | :---: | :--- |
| **id** | int(11) | PK | Não | Identificador único do usuário. |
| **nome** | varchar(255) | — | Não | Nome completo ou Razão Social. |
| **email** | varchar(255) | — | Não | E-mail de acesso (Login único). |
| **senha** | varchar(255) | — | Não | Senha criptografada em MD5. |
| **celular** | varchar(20) | — | Sim | Telefone celular principal. |
| **permissao_id** | int(11) | FK | Sim | Referência ao nível de acesso (tabela `permissoes`). |
| **primeiro_acesso** | varchar(10) | — | Sim | Status ('true'/'false') para troca obrigatória de senha. |
| **cpf_cnpj** | varchar(20) | — | Sim | Documento fiscal (CPF ou CNPJ). |
| **tipo_cliente** | varchar(10) | — | Sim | Define a natureza do perfil ('PF' ou 'PJ'). |
| **nome_fantasia** | varchar(255) | — | Sim | Nome comercial (exclusivo para PJ). |
| **data_nascimento_fundacao** | date | — | Sim | Data de nascimento (PF) ou abertura (PJ). |
| **rg** | varchar(20) | — | Sim | Registro Geral (Pessoa Física). |
| **inscricao_estadual** | varchar(50) | — | Sim | Registro fiscal estadual (Pessoa Jurídica). |
| **representante_nome** | varchar(255) | — | Sim | Nome do responsável legal da empresa. |
| **representante_cpf** | varchar(20) | — | Sim | CPF do responsável legal. |
| **representante_cargo** | varchar(100) | — | Sim | Cargo ocupado pelo representante na empresa. |
| **representante_email** | varchar(255) | — | Sim | E-mail de contato do representante. |
| **cep** | varchar(15) | — | Sim | Código de Endereçamento Postal. |
| **logradouro** | varchar(255) | — | Sim | Nome da rua ou avenida. |
| **numero** | varchar(20) | — | Sim | Número do imóvel no endereço. |
| **complemento** | varchar(100) | — | Sim | Informações adicionais (Bloco, Apto, etc.). |
| **bairro** | varchar(100) | — | Sim | Bairro de localização. |
| **cidade** | varchar(100) | — | Sim | Cidade de sede ou residência. |
| **estado** | varchar(2) | — | Sim | Sigla da Unidade Federativa (UF). |
| **telefone** | varchar(20) | — | Sim | Telefone fixo secundário. |
| **ponto_referencia** | varchar(255) | — | Sim | Referência para facilitar a localização. |
| **indicacao** | varchar(100) | — | Sim | Fonte ou pessoa que indicou o cliente. |
| **perfil_cliente** | varchar(50) | — | Sim | Classificação (Residencial, Comercial, Industrial). |
| **origem_contato** | varchar(50) | — | Sim | Meio de contato inicial (Site, Instagram, etc.). |
| **observacoes** | text | — | Sim | Notas técnicas e observações gerais. |
       

## 📝 2. Tabela: PROCESSOS

> Gerencia os processos de vistoria e licenciamento técnico vinculados aos clientes.

| Campo | Tipo | PK/FK | Nulo | Descrição |
| :--- | :--- | :---: | :---: | :--- |
| **id** | int(11) | PK | Não | Identificador único do processo. |
| **numero_processo** | varchar(50) | — | Não | Protocolo oficial da vistoria. |
| **descricao** | text | — | Sim | Histórico e detalhes da ocorrência. |
| **tipo_processo** | varchar(100) | — | Sim | Categoria do licenciamento (Ex: Renovação). |
| **status** | varchar(50) | — | Sim | Fase atual (Em Análise, Aprovado, Pendente). |
| **data_criacao** | datetime | — | Sim | Data e hora em que o processo foi aberto. |
| **cliente_id** | int(11) | FK | Sim | ID do cliente (usuário) dono do processo. |

## 📜 3. Tabela: DOCUMENTOS

> Registra o controle dos arquivos digitais (PDF/Imagens) armazenados no servidor.

| Campo | Tipo | PK/FK | Nulo | Descrição |
| :--- | :--- | :---: | :---: | :--- |
| **id** | int(11) | PK | Não | Identificador único do documento. |
| **codigo_identificador**| varchar(50) | — | Não | Código único de rastreio (Ex: DOC-XXXX). |
| **tipo_documento** | varchar(100) | — | Sim | Categoria do arquivo (Laudo, Planta, etc.). |
| **caminho_arquivo** | varchar(255) | — | Sim | Nome do arquivo físico na pasta `/uploads`. |
| **data_criacao** | datetime | — | Sim | Data e hora em que o upload foi realizado. |
| **usuario_id** | int(11) | FK | Sim | ID do usuário que realizou o upload. |

## 🔑 4. Tabela: PERMISSOES
> Define os níveis de privilégios para o controle de acesso (ACL).

| Campo | Tipo | PK/FK | Nulo | Descrição |
| :--- | :--- | :---: | :---: | :--- |
| **id** | int(11) | PK | Não | Identificador do nível de acesso. |
| **nome** | varchar(50) | — | Não | Perfil (1: Admin, 2: Cliente, 3: Gestor). |


## 🔗 Resumo dos Relacionamentos

### USUARIOS (1) — (N) PROCESSOS
Um cliente pode possuir múltiplos processos registrados. A relação ocorre via `processos.cliente_id` → `usuarios.id`.

### USUARIOS (1) — (N) DOCUMENTOS
Um usuário pode realizar o upload de diversos documentos técnicos. A relação ocorre via `documentos.usuario_id` → `usuarios.id`.

### PERMISSOES (1) — (N) USUARIOS
Cada nível de acesso define o perfil de múltiplos usuários. A relação ocorre via `usuarios.permissao_id` → `permissoes.id`.

---