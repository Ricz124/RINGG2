let draggedCard = null;
let draggedColumn = null;
let activeCard = null;
let boardData = { columns: [] };  // JSON que armazenará todas as informações

document.addEventListener("DOMContentLoaded", () => {
    const cardTitleInput = document.getElementById("cardTitle");
    if (cardTitleInput) {
        cardTitleInput.addEventListener("input", (event) => {
            if (activeCard) {
                activeCard.querySelector(".card-title").textContent = event.target.value;
                updateCardData(activeCard, 'title', event.target.value);
            }
        });
    }
});

// Atualiza os dados de um card específico
function updateCardData(card, key, value) {
    const columnElement = card.closest(".column");
    const columnId = parseInt(columnElement.dataset.columnId, 10); // Certifique-se de que é um número
    const cardId = card.dataset.cardId;

    console.log("Column ID:", columnId); // Verifique o ID da coluna
    console.log("Card ID:", cardId); // Verifique o ID do cartão

    const column = boardData.columns.find(col => col.id === columnId);
    if (!column) {
        console.error("Column not found for ID:", columnId);
        return;
    }

    const cardData = column.cards.find(c => c.id === cardId);
    if (!cardData) {
        console.error("Card not found for ID:", cardId);
        return;
    }

    cardData[key] = value;
}

// Função para adicionar uma nova coluna
function addColumn() {
    const board = document.getElementById("board");
    const columnId = parseInt(`${boardData.columns.length + 1}`);

    const column = document.createElement("div");
    column.className = "column";
    column.draggable = true;
    column.ondragstart = dragColumn;
    column.ondragover = allowDrop;
    column.ondrop = dropColumn;
    column.dataset.columnId = columnId;
    column.innerHTML = `
      <h2 onclick="editColumnTitle(this)">Nova Coluna</h2>
      <input type="text" onblur="saveColumnTitle(this)" style="display: none;">
      <div class="card-container" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
      <button onclick="addCard(this)">Adicionar Card</button>
      <button onclick="deleteColumn(this)">Deletar Coluna</button>
    `;

    board.appendChild(column);

    boardData.columns.push({
        id: columnId,
        title: `Nova Coluna`,
        cards: []
    });
}

// Função para adicionar um novo cartão
function addCard(button) {
    const cardContainer = button.previousElementSibling;
    const columnElement = cardContainer.closest(".column");
    const columnId = parseInt(columnElement.dataset.columnId, 10); // Certifique-se de que é um número
    console.log("Column ID:", columnId); // Verifique o ID da coluna

    const column = boardData.columns.find(col => col.id === columnId);
    if (!column) {
        console.error("Column not found for ID:", columnId);
        return;
    }

    const cardId = `card${column.cards.length + 1}`;

    const card = document.createElement("div");
    card.className = "card";
    card.draggable = true;
    card.ondragstart = dragCard;
    card.ondragover = allowDrop;
    card.ondrop = dropCard;
    card.onclick = () => openModal(card);
    card.dataset.cardId = cardId;
    card.dataset.creationDate = new Date().toLocaleDateString();
    card.dataset.color = "#ffffff";
    card.innerHTML = `<span class="card-title" onclick="editCardTitle(this)">Novo Card</span>
                      <input type="text" onblur="saveCardTitle(this)" style="display: none;">`;

    cardContainer.appendChild(card);

    column.cards.push({
        id: cardId,
        title: "Novo Card",
        creationDate: card.dataset.creationDate,
        dueDate: "",
        color: "#ffffff",
        tasks: []
    });
}

// Funções de arrastar e soltar colunas e cartões
function dragColumn(event) {
    draggedColumn = event.target;
}

function dropColumn(event) {
    event.preventDefault();
    const targetColumn = event.target.closest(".column");
    if (draggedColumn && targetColumn && draggedColumn !== targetColumn) {
        const board = document.getElementById("board");
        board.insertBefore(draggedColumn, targetColumn.nextSibling);
        draggedColumn = null;
    }
}

function dragCard(event) {
    draggedCard = event.target;
}

function dropCard(event) {
    event.preventDefault();
    const targetCard = event.target.closest(".card");
    if (draggedCard && targetCard && draggedCard !== targetCard) {
        const parent = targetCard.parentNode;
        parent.insertBefore(draggedCard, targetCard.nextSibling);
        draggedCard = null;
    }
}

function allowDrop(event) {
    event.preventDefault();
}

// Modal de detalhes do cartão
function openModal(card) {
    activeCard = card;
    const columnElement = card.closest(".column");
    const columnId = parseInt(columnElement.dataset.columnId, 10); // Certifique-se de que é um número
    const cardId = card.dataset.cardId;

    console.log("Column ID:", columnId); // Verifique o ID da coluna
    console.log("Card ID:", cardId); // Verifique o ID do cartão

    const column = boardData.columns.find(col => col.id === columnId);
    if (!column) {
        console.error("Column not found for ID:", columnId);
        return;
    }

    const cardData = column.cards.find(c => c.id === cardId);
    if (!cardData) {
        console.error("Card not found for ID:", cardId);
        return;
    }

    document.getElementById("cardModal").style.display = "block";
    document.getElementById("cardTitle").value = cardData.title;
    document.getElementById("creationDate").textContent = `Data de Criação: ${cardData.creationDate}`;
    document.getElementById("dueDate").value = cardData.dueDate || "";
    document.getElementById("cardColorPicker").value = cardData.color;

    loadCheckboxes(cardData.tasks);
}

function closeModal() {
    document.getElementById("cardModal").style.display = "none";

    if (activeCard) {
        const dueDate = document.getElementById("dueDate").value;
        updateCardData(activeCard, 'dueDate', dueDate);

        const color = document.getElementById("cardColorPicker").value;
        updateCardData(activeCard, 'color', color);
        activeCard.style.backgroundColor = color;
    }
    
    saveCheckboxes();
}

// Funções para edição de título do card e da coluna
function saveCardTitle(input) {
    const cardTitle = input.previousElementSibling;
    cardTitle.textContent = input.value;
    input.style.display = "none";
    cardTitle.style.display = "inline";
    updateCardData(activeCard, 'title', input.value);
}

function editCardTitle(titleElement) {
    const input = titleElement.nextElementSibling;
    input.value = titleElement.textContent;
    titleElement.style.display = "none";
    input.style.display = "inline";
    input.focus();
}

function editColumnTitle(titleElement) {
    const input = titleElement.nextElementSibling;
    input.value = titleElement.textContent;
    titleElement.style.display = "none";
    input.style.display = "inline";
    input.focus();
}

function saveColumnTitle(input) {
    const columnTitle = input.previousElementSibling;
    columnTitle.textContent = input.value;
    input.style.display = "none";
    columnTitle.style.display = "inline";

    // Obtém o ID da coluna a partir do atributo 'data-column-id'
    const columnId = input.closest(".column").dataset.columnId;

    // Busca a coluna no boardData.columns com base no ID
    const column = boardData.columns.find(col => col.id === parseInt(columnId)); // Usando parseInt para garantir que o ID seja numérico

    // Verifica se a coluna foi encontrada
    if (column) {
        // Atualiza o título da coluna
        column.title = input.value;
    } else {
        console.error("Coluna não encontrada:", columnId);
    }
}

// Funções para checkbox de tarefas
function addCheckbox() {
    const taskList = document.getElementById("taskList");
    const li = document.createElement("li");
    li.innerHTML = `<input type="checkbox"> <input type="text" placeholder="Nova Tarefa">`;
    taskList.appendChild(li);
}

function loadCheckboxes(tasks) {
    const taskList = document.getElementById("taskList");
    taskList.innerHTML = "";
    tasks.forEach(task => {
        const li = document.createElement("li");
        li.innerHTML = `<input type="checkbox" ${task.completed ? "checked" : ""}> <input type="text" value="${task.text}">`;
        taskList.appendChild(li);
    });
}

function saveCheckboxes() {
    const taskList = document.getElementById("taskList").children;
    const tasks = Array.from(taskList).map(li => ({
        text: li.querySelector("input[type='text']").value,
        completed: li.querySelector("input[type='checkbox']").checked,
    }));

    if (!activeCard) {
        console.error("No active card selected.");
        return;
    }

    const columnElement = activeCard.closest(".column");
    const columnId = parseInt(columnElement.dataset.columnId, 10); // Certifique-se de que é um número
    const cardId = activeCard.dataset.cardId;

    console.log("Column ID:", columnId); // Verifique o ID da coluna
    console.log("Card ID:", cardId); // Verifique o ID do cartão

    const column = boardData.columns.find(col => col.id === columnId);
    if (!column) {
        console.error("Column not found for ID:", columnId);
        return;
    }

    const cardData = column.cards.find(c => c.id === cardId);
    if (!cardData) {
        console.error("Card not found for ID:", cardId);
        return;
    }

    cardData.tasks = tasks;
}

function deleteColumn(button) {
    const column = button.parentElement;
    const columnId = parseInt(column.dataset.columnId, 10); // Certifique-se de que é um número

    console.log("Column ID:", columnId); // Verifique o ID da coluna

    boardData.columns = boardData.columns.filter(col => col.id !== columnId);
    column.remove();
}

function deleteCard() {
    if (activeCard) {
        const columnElement = activeCard.closest(".column");
        const columnId = parseInt(columnElement.dataset.columnId, 10); // Certifique-se de que é um número
        const cardId = activeCard.dataset.cardId;

        console.log("Column ID:", columnId); // Verifique o ID da coluna
        console.log("Card ID:", cardId); // Verifique o ID do cartão

        const column = boardData.columns.find(col => col.id === columnId);
        if (!column) {
            console.error("Column not found for ID:", columnId);
            return;
        }

        column.cards = column.cards.filter(c => c.id !== cardId);

        activeCard.remove();
        closeModal();
    }
}

function printBoardData() {
    console.log(JSON.stringify(boardData, null, 2));
}

function sendBoardDataToServer() {
    const jsonData = JSON.stringify(boardData);

    fetch("php/processamentoDDados.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: jsonData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Erro na requisição ao servidor");
        }
        // Verifica se a resposta é JSON antes de interpretá-la
        return response.json().catch(() => {
            throw new Error("Resposta do servidor não é um JSON válido");
        });
    })
    .then(data => {
        console.log("Resposta do servidor:", data);
    })
    .catch(error => {
        console.error("Erro ao enviar dados:", error);
    });


}

function mandarColunaPuBanco() {
    const jsonData = JSON.stringify(boardData);

    fetch("php/inserirColuna.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: jsonData
    })
    .then(response => {
        // Verifica se a resposta é bem-sucedida
        if (!response.ok) {
            return response.text().then(text => { 
                throw new Error(`Erro na requisição ao servidor: ${text}`);
            });
        }
        // Verifica se a resposta é JSON antes de interpretá-la
        return response.json().catch(() => {
            throw new Error("Resposta do servidor não é um JSON válido");
        });
    })
    .then(data => {
        console.log("Resposta do servidor:", data);
    })
    .catch(error => {
        console.error("Erro ao enviar dados:", error);
    });
}

function mandarCardsPuBanco() {
    const jsonData = JSON.stringify(boardData);

    fetch("php/inserirCards.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: jsonData
    })
    .then(response => {
        // Verifica se a resposta é bem-sucedida
        if (!response.ok) {
            return response.text().then(text => { 
                throw new Error(`Erro na requisição ao servidor: ${text}`);
            });
        }
        // Verifica se a resposta é JSON antes de interpretá-la
        return response.json().catch(() => {
            throw new Error("Resposta do servidor não é um JSON válido");
        });
    })
    .then(data => {
        console.log("Resposta do servidor:", data);
    })
    .catch(error => {
        console.error("Erro ao enviar dados:", error);
    });
}

// Função para carregar os dados das colunas via AJAX
function loadColumns() {
    fetch('php/carregarColunas.php') // Arquivo PHP que retorna o JSON
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Erro:', data.error);
            } else {
                displayColumns(data.columns); // Função para exibir as colunas
            }
        })
        .catch(error => {
            console.error('Erro ao carregar os dados:', error);
        });
}

function displayColumns(columns) {
    const board = document.getElementById("board");
    board.innerHTML = ''; // Limpa o conteúdo anterior

    columns.forEach(column => {
        const columnElement = document.createElement("div");
        columnElement.className = "column";
        columnElement.draggable = true;
        columnElement.ondragstart = dragColumn;
        columnElement.ondragover = allowDrop;
        columnElement.ondrop = dropColumn;

        // Verifique se o id da coluna está sendo atribuído corretamente
        columnElement.dataset.columnId = column.id; // Atribui o id da coluna ao dataset

        columnElement.innerHTML = `
            <h2 onclick="editColumnTitle(this)">${column.title}</h2>
            <input type="text" onblur="saveColumnTitle(this)" style="display: none;">
            <div class="card-container" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
            <button onclick="addCard(this)">Adicionar Card</button>
            <button onclick="deleteColumn(this)">Deletar Coluna</button>
        `;

        board.appendChild(columnElement);

        // Atualiza boardData.columns
        boardData.columns.push({
            id: column.id,
            title: column.title,
            cards: []
        });
    });
}

// Carregar as colunas ao carregar a página
window.onload = loadColumns;