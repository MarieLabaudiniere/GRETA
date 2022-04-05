<div class="container mt-3 border border-secondary rounded">
    <input class="m-5" type="text" id="lib" placeholder="libellé">
    <button id="ajout" class="btn btn-dark">Ajouter</button>
</div>

<div class="container mt-3">
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>Libellé catégorie</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
</div>
<script>
    $(function() {
        $('#ajout').on('click', function() {
            const valLibelle = $('#lib').val();
            $('tbody').append("<tr><td>" + valLibelle + "</td><td>" + getFormatDateHeureFr(new Date()) +
                "</td><td><a href=\"#\" id=\"supp\" onclick=\"deleteRow(this)\"><img src=\"public/medias/trash.svg\"></a></td></tr>");
        })
    });

    function deleteRow(elem) {
        $(elem).closest("tr").remove();
    }
</script>