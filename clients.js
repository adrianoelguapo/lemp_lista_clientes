document.addEventListener("DOMContentLoaded", function() {
    const container = document.querySelector(".container");

    // Función para renderizar una tarjeta de cliente
    function renderClientCard(client) {
        const card = document.createElement("div");
        card.className = "card";

        const img = document.createElement("img");
        img.src = client.image_url;
        img.alt = `Foto de ${client.name}`;
        card.appendChild(img);

        const name = document.createElement("p");
        name.className = "name";
        name.textContent = client.name;
        card.appendChild(name);

        const surnames = document.createElement("p");
        surnames.className = "surnames";
        surnames.textContent = client.surnames;
        card.appendChild(surnames);

        const company = document.createElement("p");
        company.className = "company";
        company.textContent = client.company;
        card.appendChild(company);

        // Botón de Eliminar
        const deleteButton = document.createElement("button");
        deleteButton.className = "delete-btn";
        deleteButton.textContent = "Eliminar";
        deleteButton.addEventListener("click", function() {
            card.remove();
            fetch("delete_client.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id=${client.id}`
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    console.log("Cliente eliminado correctamente");
                } else {
                    console.error("Error al eliminar el cliente");
                }
            });
        });
        card.appendChild(deleteButton);

        // Botón de Editar
        const editButton = document.createElement("button");
        editButton.className = "editCard";
        editButton.textContent = "Editar";
        editButton.addEventListener("click", function() {
            openEditModal(client, card);
        });
        card.appendChild(editButton);

        container.appendChild(card);
    }

    // Obtener y mostrar los clientes
    fetch("fetch_clients.php")
        .then(response => response.json())
        .then(clients => clients.forEach(renderClientCard))
        .catch(error => console.error("Error al cargar los clientes:", error));

    // Modal de Agregar Cliente
    const addClientButton = document.getElementById("addClientButton");
    const addClientModal = document.getElementById("addClientModal");
    const closeModal = document.getElementById("closeModal");

    addClientButton.addEventListener("click", () => addClientModal.style.display = "flex");
    closeModal.addEventListener("click", () => addClientModal.style.display = "none");

    document.getElementById("addClientForm").addEventListener("submit", function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch("add_client.php", { method: "POST", body: formData })
        .then(response => response.json())
        .then(client => {
            renderClientCard(client);
            addClientModal.style.display = "none";
            this.reset();
        })
        .catch(error => console.error("Error al agregar cliente:", error));
    });

    // Modal de Editar Cliente
    const editClientModal = document.getElementById("editClientModal");
    const closeEditModal = document.getElementById("closeEditModal");

    function openEditModal(client, cardElement) {
        document.getElementById("editId").value = client.id;
        document.getElementById("editName").value = client.name;
        document.getElementById("editSurnames").value = client.surnames;
        document.getElementById("editCompany").value = client.company;
        document.getElementById("editImageUrl").value = client.image_url;
        
        editClientModal.style.display = "flex";
        
        document.getElementById("editClientForm").onsubmit = function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch("edit_client.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(updatedClient => {
                // Actualizar la tarjeta de cliente
                cardElement.querySelector(".name").textContent = updatedClient.name;
                cardElement.querySelector(".surnames").textContent = updatedClient.surnames;
                cardElement.querySelector(".company").textContent = updatedClient.company;
                cardElement.querySelector("img").src = updatedClient.image_url;
                
                editClientModal.style.display = "none";
            })
            .catch(error => console.error("Error al editar cliente:", error));
        };
    }

    closeEditModal.addEventListener("click", () => editClientModal.style.display = "none");
});
