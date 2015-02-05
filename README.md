# NoScope
Plataforma de distribuição de jogos através de moeda virtual, feito em PHP, MySQL e Bootstrap 3 para parte visual, há também algumas funções em AJAX.
Esse ssistema foi feito como Trabalho de Conclusão de Curso para o curso Técnico em Informática do Insituto Federal do Sul de Minas Gerais Campus Inconfidentes.

### DEMO
[http://github.adriel.eu/NoScope](http://github.adriel.eu/NoScope)
#### CREDENCIAIS ADMINSTRADOR
User: admin

Pass: admin

### Instalação:
#### Requerimentos:
- Apache 2, NGINX ou outro servidor HTTP
- PHP 5.4+
- MYSQL 5.6+

#### Instalando o sistema básico
1. Faça o download dos arquivos desse repositório e coloque dentro da pasta **www** do seu servidor  HTTP preferido (apache, nginx ou outro);
2. Importe o arquivo db.sql para o seu servidor MySQL
3. Altere os dados de conexão do MySQL no arquivo _/classes/mysql.php_
4. Altere as configurações básicas no arquivo _/libs/constants.php_
5. Divirta-se

#### Instalando o chat

1. Abra o arquivo ajax/chat_server.php
2. Edite as linhas:

> $host = '172.28.1.145'; //Com o IP do servidor que está como host

> $port = '9000'; //Com a porta que você quer que o Chat funcione

3. No arquivo _js/chat.js_ Altere a linha var **wsUri = "ws://"+DOMAIN+":9000/NoScope/ajax/chat_server.php";** com o Domínio, Porta e Diretórios corretos para acessar o chat.
4. Inicie o servidor de Chat com o comando > php -q ajax/chat_server.php

----------------------------------------------------------------------------------------
- Desenvolvido por Adriel Cardoso dos Santos(Turox)
- Professor orientador Ivan Paulino Pereira
