$(document).ready(function() {
    // Ajouter un produit 
    $("#add-form").submit(function(event) {
        event.preventDefault(); 

        $.ajax({
            url: "../php/addProduct.php",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.trim() === "success") {
                    alert("Produit ajouté avec succès !");
                    $("#add-form")[0].reset();
                    location.reload();
                } else {
                    alert(response);
                }
            },
            error: function() {
                alert("Erreur lors de l'ajout du produit.");
            }
        });
    });

    // Mettre à jour un produit 
    $("#product-table").on("click", ".update-btn", function() {
        const row = $(this).closest("tr");
        const productID = row.data("id");
        const quantity = row.find(".quantity").val();

        $.ajax({
            url: "../php/updateProduct.php",
            type: "POST",
            data: { prodID: productID, quantity: quantity },
            success: function(response) {
                if (response.trim() === "success") {
                    alert("Produit mis à jour !");
                } else {
                    alert("Erreur : " + response);
                }
            },
            error: function() {
                alert("Erreur lors de la mise à jour.");
            }
        });
    });

    // Supprimer un produit 
    $("#product-table").on("click", ".delete-btn", function() {
        if (confirm("Voulez-vous supprimer ce produit ?")) {
            const row = $(this).closest("tr");
            const productID = row.data("id");

            $.ajax({
                url: "../php/deleteProduct.php",
                type: "POST",
                data: { prodID: productID },
                success: function(response) {
                    if (response.trim() === "success") {
                        row.remove();
                        alert("Produit supprimé !");
                    } else {
                        alert("Erreur : " + response);
                    }
                },
                error: function() {
                    alert("Erreur lors de la suppression.");
                }
            });
        }
    });
});
