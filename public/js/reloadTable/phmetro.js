function atualizaTabela(data) {
    let tbody = document.querySelector('tbody');
    tbody.innerHTML = '';

    if (Array.isArray(data)) {
        data.forEach(item => {
            let date = new Date(item.data_hora_atualizacao);
            let dataFormatada = date.toLocaleString('pt-BR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
            });

            let row = `
                <tr class="text-center clickable-row" style="cursor: pointer;" 
                    onmouseover="this.style.backgroundColor='#cce4ff';" 
                    onmouseout="this.style.backgroundColor='';">
                    <td class="py-2 px-4 border-b">${dataFormatada}</td>
                    <td class="py-2 px-4 border-b">${item.ph}</td>
                    <td class="py-2 px-4 border-b">${item.escala}</td>
                    <td class="py-2 px-1 border-b">${item.id_fk_esp_macAdress}</td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    } else {
        console.error('Dados inválidos para atualizar a tabela:', data);
    }
}

function recarregarTabela() {
    fetch('/recarregarDadosPhmetro')
    .then(response => response.json())
    .then(data => {
        if (Array.isArray(data)) {
            atualizaTabela(data);
        } else {
            console.error('Dados inválidos recebidos:', data);
            atualizaTabela([]);
        }
    })
    .catch(error => console.error('ERRO AO ATUALIZAR A TABELA:', error));
}

recarregarTabela();
setInterval(recarregarTabela, 10000);