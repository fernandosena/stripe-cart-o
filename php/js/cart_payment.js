$(document).ready(function() {
    function checkCookie(cookieName) {
        let nameEQ = cookieName + "=";
        let ca = document.cookie.split(';');
        for(let i=0;i < ca.length;i++) {
            let c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) === 0) return true;
        }
        return false;
    }

    function excluirCookie(nomeCookie) {
        document.cookie = nomeCookie + "=; expires=Thu, 01 Jan 1970 00:00:01 GMT;";
    }
    
    if (checkCookie("cart")) {
        const cookieValue = getCookie("cart");

        $.ajax({
            url: url+"/create-payment", // URL do arquivo que será chamado
            type: "POST", // Tipo da requisição (GET, POST, etc)
            data: JSON.stringify(cookieValue),
            contentType: 'application/json',
            success: function(response) {
                // Função que será executada em caso de sucesso
                console.log("Sucesso: " + response);

            },
            error: function(xhr, status, error) {
                // Função que será executada em caso de erro
                console.error("Erro: " + error);
            }
        });
        excluirCookie("cart");
    }
});
  

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}
  