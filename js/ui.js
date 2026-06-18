const formCadastro = document.getElementById('formCadastro');
const divMensagem = document.getElementById('mensagem');

if (formCadastro) {
    formCadastro.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(formCadastro);
        const jogadores = formData.getAll('jogadores[]');

        const jogadoresValidos = jogadores.map(j => j.trim()).filter(j => j !== '');

        if (jogadoresValidos.length !== 8) {
            divMensagem.style.color = 'red';
            divMensagem.textContent = 'Por favor, preencha o nome dos 8 jogadores.';
            return;
        }

        fetch('salvar_participantes.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ jogadores: jogadoresValidos })
        })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    divMensagem.style.color = 'green';
                    divMensagem.textContent = 'Participantes salvos com sucesso! Avançando...';
                    setTimeout(() => {
                        window.location.href = '../configuracao/configuracao.php';
                    }, 1500);
                } else {
                    divMensagem.style.color = 'red';
                    divMensagem.textContent = 'Erro ao salvar: ' + data.erro;
                }
            })
            .catch(error => {
                divMensagem.style.color = 'red';
                divMensagem.textContent = 'Erro de comunicação com o servidor.';
            });
    });
}

const formConfiguracao = document.getElementById('formConfiguracao');
const divMensagemConfig = document.getElementById('mensagemConfig');

if (formConfiguracao) {
    formConfiguracao.addEventListener('submit', function (e) {
        e.preventDefault();

        const formato = document.querySelector('input[name="formato"]:checked').value;

        fetch('gerar_rodadas.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ formato: formato })
        })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    divMensagemConfig.style.color = 'green';
                    divMensagemConfig.textContent = 'Rodadas geradas com sucesso! Avançando...';
                    setTimeout(() => {
                        window.location.href = '../rodadas/rodadas.php';
                    }, 1500);
                } else {
                    divMensagemConfig.style.color = 'red';
                    divMensagemConfig.textContent = 'Erro: ' + data.erro;
                }
            })
            .catch(error => {
                divMensagemConfig.style.color = 'red';
                divMensagemConfig.textContent = 'Erro de comunicação com o servidor.';
            });
    });
}

const formPlacares = document.getElementById('formPlacares');
const divMensagemRodada = document.getElementById('mensagemRodada');

if (formPlacares) {
    formPlacares.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(formPlacares);
        const dados = Object.fromEntries(formData.entries());

        fetch('salvar_placar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(dados)
        })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    divMensagemRodada.style.color = 'green';
                    divMensagemRodada.textContent = 'Placares salvos! Carregando próxima rodada...';
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    divMensagemRodada.style.color = 'red';
                    divMensagemRodada.textContent = 'Erro: ' + data.erro;
                }
            })
            .catch(error => {
                divMensagemRodada.style.color = 'red';
                divMensagemRodada.textContent = 'Erro de comunicação com o servidor.';
            });
    });
}