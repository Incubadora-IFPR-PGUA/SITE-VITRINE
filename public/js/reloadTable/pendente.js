document.addEventListener('DOMContentLoaded', () => {
    const loadingScreen = document.getElementById('loadingScreen');
    loadingScreen.style.display = 'none';
});

function atualizaTabela(data) {
    let tableBody = document.querySelector('table tbody');
    let theadBody = document.querySelector('thead tr');
    tableBody.innerHTML = '';

    if (data.length === 0) {
        theadBody.innerHTML = '';
        tableBody.innerHTML = `
            <tr>
                <td colspan="3" class="py-6 text-lg text-gray-600 text-center">
                    <div style="display: flex; flex-direction: column; align-items: center;">
                        <img src="galinha.png" alt="Nenhum registro encontrado" style="width: 150px; margin-bottom: 10px;">
                        <span>Nenhum registro foi encontrado.</span>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    theadBody.innerHTML = `
        <th class="py-2 text-center px-4 w-5/12">Nome</th>
        <th class="py-2 text-center px-4 w-2/12">Anilha</th>
        <th class="py-2 text-center px-4 w-2/12">Data/Hora - Solicitação</th>
    `;

    data.forEach(function(item) {
        let row = `<tr class="clickable-row" 
                data-id="${item.id}" 
                data-name="${item.nome}" 
                data-codigo="${item.numero_anilha}" 
                style="cursor: pointer;" 
                onmouseover="this.style.backgroundColor='#cce4ff';" 
                onmouseout="this.style.backgroundColor='';">
                <td class="py-2 px-4 border-b border-gray-300 text-center">${item.nome}</td>
                <td class="py-2 px-4 border-b border-gray-300 text-center">${item.numero_anilha}</td>
                <td class="py-2 px-4 border-b border-gray-300 text-center">${item ? new Date(item.updated_at).toLocaleString() : 'N/A'}</td>
            </tr>`;

        tableBody.innerHTML += row;
    });

    let currentId;

    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', function() {
            const name = this.getAttribute('data-name');
            const codigo = this.getAttribute('data-codigo');
            const id = this.getAttribute('data-id');
            currentId = id;
            currentName = name;

            const viewForm = document.getElementById('viewForm');
            const btViewDelete = document.getElementById('btViewDelete');
            const btViewAccept = document.getElementById('btViewAccept');

            viewForm.action = `/pendenteDelete/${id}`;
            btViewAccept.style.display = 'inline';

            viewForm.style.display = 'block';
            btViewDelete.style.display = 'inline';

            document.getElementById('viewName').value = name;
            document.getElementById('viewCodigo').value = codigo;
    
            const modal = new bootstrap.Modal(document.getElementById('MyModal'));
            modal.show();
        });    
    });

    document.getElementById('btViewAccept').addEventListener('click', () => {
        if (currentId) {
            const updatedName = document.getElementById('viewName').value;
            const loadingScreen = document.getElementById('loadingScreen');
            loadingScreen.style.display = 'flex';
    
            fetch(`/aceitarPendente/${currentId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ name: updatedName })
            })
            .then(response => {
                // nao botei nada pq sempre da erro
            })
            .catch(error => {
                console.error('Erro ao aceitar o registro:', error);
                alert('Erro ao aceitar o registro.');
            })
            .finally(() => {
                recarregarTabela();
                const modal = bootstrap.Modal.getInstance(document.getElementById('MyModal'));
                modal.hide();
                loadingScreen.style.display = 'none';
            });
        }
    });
}

function recarregarTabela() {
    fetch('/pendenteReload')
    .then(response => response.json())
    .then(data => {
        atualizaTabela(data);
    })
    .catch(error => console.error('Erro ao atualizar a tabela:', error));
}

recarregarTabela();
setInterval(recarregarTabela, 10000);