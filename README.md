#Super 8 - Sistema de Gestão de Beach Tennis 

A aplicação foi construída utilizando arquitetura em camadas, separando completamente as responsabilidades do back-end (PHP) e do front-end (HTML/CSS/JS).
Para atender aos requisitos do trabalho, a persistência de dados foi feita **100% via arquivos JSON**, sem a utilização de banco de dados relacional.

---

##Como rodar o projeto localmente

Como o sistema é processado em PHP puro e manipula arquivos diretamente na pasta, é necessário um servidor local como **WAMP**, **XAMPP** rodando o Apache.

1. Baixe o ZIP do projeto ou faça o clone do repositório.
2. Extraia a pasta raiz do projeto para dentro do diretório público do seu servidor web:
   * Se for WAMP: coloque em `C:\wamp64\www\Super8`
   * Se for XAMPP: coloque em `C:\xampp\htdocs\Super8`
3. Abra o painel de controle do seu servidor local e garanta que o serviço do **Apache** está iniciado.
4. Abra o seu navegador e acesse a URL: `http://localhost/Super8/`
5. O sistema iniciará na tela inicial. Para começar, clique no botão **"Novo Torneio"**.

**Nota sobre permissões:** O PHP precisa criar, ler e editar arquivos dentro da pasta `data/` (onde ficam o `participantes.json`, `rodadas.json` e `historico.json`). 
Em instalações padrão do Windows isso não é problema, mas em ambientes Linux/Mac, garanta que a pasta `data/` tenha permissão de escrita (`chmod 777`).

---

## Tecnologias Utilizadas

* **Back-end:** PHP (Toda a lógica de negócios, sorteios, cálculo de pontuação e gravação nos arquivos JSON).
* **Front-end:** HTML5, CSS3 (variáveis, flexbox, CSS Grid) e JavaScript Vanilla.
* **Comunicação:** API Fetch do JS para chamadas assíncronas ao PHP (o que garante que a tela e o placar atualizem sem "piscar" a página).
* **Banco de Dados:** Arquivos físicos `.json` gerenciados exclusivamente pelo servidor.

---

##Regras de Pontuação e Desempate

O sistema automatiza um torneio de chaveamento rápido para 8 jogadores. O cálculo para a tabela de classificação ("Ranking")
é feito em tempo real a cada rodada finalizada.

### Como a pontuação é calculada:
* **Vitória:** O jogador que compõe a dupla vencedora da partida recebe **1 Ponto** na tabela.
* **Derrota:** O jogador recebe **0 Pontos**.

### Siglas da Tabela de Classificação:
* **J:** Jogos disputados até o momento.
* **V e D:** Vitórias e Derrotas.
* **GV (Games Vencidos):** Somatória de todos os games (pontos do set) que a equipe do jogador fez.
* **GP (Games Perdidos):** Somatória de todos os games que a equipe do jogador levou dos adversários.
* **SG (Saldo de Games):** A diferença entre os games que ele venceu e os que perdeu (GV - GP).

### Critérios de Desempate
Como é comum que muitos jogadores terminem o torneio com a mesma quantidade de pontos (mesmo número de vitórias), a lógica em PHP do sistema aplica o seguinte critério de desempate, em ordem de importância:

1. **Maior Saldo de Games (SG):** Quem tiver a maior diferença positiva entre GV e GP fica na frente.
2. **Maior quantidade de Games Vencidos (GV):** Se o SG for igual, quem tiver pontuado mais vezes durante o torneio leva a vantagem.
3. **Ordem de Inscrição (ID):** Se houver um empate absoluto nos três critérios anteriores, o sistema mantém a ordem em que os jogadores foram cadastrados no sistema.

---

##Diferenciais Implementados
* **Histórico de Torneios:** Os torneios não somem ao serem finalizados. Eles são compactados e armazenados em um arquivo de histórico para consulta futura.
* **Impressão/PDF do Ranking:** Regras específicas de `@media print` no CSS para limpar a interface e imprimir apenas a tabela de forma profissional.
* **Correção de Placares:** Tela de edição para corrigir erros de digitação do organizador em rodadas passadas.

*  **LINK PARA VIDEO NO YOUTUBE
*  https://www.youtube.com/watch?v=xivYc2M7LRw
