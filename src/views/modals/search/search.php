<div class="modal fade" id="modal-search">
  <div class="modal-dialog modal-lg" role="document" style="top: 5%">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white border-0 pb-1">
        <input class="form-control bg-dark text-white h-100 border-3 border-primary shadow-none" id="fuzzySearch" value="" placeholder="Search I.E Ubuntu"/>
      </div>
      <div class="modal-body bg-dark text-white pt-0">
          <!-- <div id="searchLoading" class="text-center py-5">
              <h4 class="mb-0"><i class="fas fa-cog fa-spin"></i></h4>
          </div> -->
          <div id="searchResults">
              <div id="resultCount">0 Results</div>
              <div class="list-group" id="searchResultsList" style="max-height: 60vh; overflow-y: scroll">
              </div>
         </div>
      </div>
    </div>
  </div>
</div>
<script>

var lastSearch = "";

$("#modal-search").on("hidden.bs.modal", function(){
    $("#fuzzySearch").val("")
    $("#searchResultsList").empty();
    $("#resultCount").text(`0 Results`)
});

$("#modal-search").on("shown.bs.modal", function(){
    $("#fuzzySearch").val("").focus();
});

$('#modal-search').on('click', ".searchResult", function() {
    router.navigate($(this).data("href"))
    $("#modal-search").modal("hide")
});

$('#fuzzySearch').on('keyup', function() {
    let search = $(this).val()

    if(search == ""){
        $("#resultCount").text(`0 Results`)
        $("#searchResultsList").empty();
        return false;
    }

    if(search == lastSearch){
        return false;
    }

    lastSearch = search;

    ajaxRequest('/api/Search/SearchIndexController/get', {search}, function(data){
        data = makeToastr(data);
        let iconMap = {
            instances: "box",
            networks: "network-wired",
            images: "image",
            profiles: "user",
            storage: "hdd"
        }
        let entityToUrlMap = {
            instances: "instance",
            networks: "networks",
            images: "images",
            profiles: "profiles",
            storage: "storage"
        }

        let html = "";
        $.each(data, (_, result)=>{
            let hostAlias = hostsAliasesLookupTable[result.hostId];
            let name = result.name.length > 30 ? `${result.name.substring(0, 30)}...` : result.name
            html += `<a data-href="/${entityToUrlMap[result.entity]}/${hostAlias}/${result.name}" class="list-group-item list-group-item-action list-group-item-dark searchResult" style="cursor: pointer;">
              <h5 class="mb-1"><i class="fas fa-${iconMap[result.entity]} me-2"></i><span class="text-truncate">${name}</span></h5>
              <p class="mb-1"><i class="fas fa-server me-2"></i>${hostAlias} <i class="fas fa-project-diagram ms-2"></i> ${result.project}</p>`

             $.each(Object.keys(result.matches.matches).slice(0, 2), (_, key)=>{
                html += `<small class="d-block"><b>${key}</b>: ${result.matches.matches[key]}</small>`
             });
             let noOfKeys = Object.keys(result.matches.matches).length
             if(noOfKeys > 3){
                 html += `<small class="d-block"><b>+${noOfKeys} more matches</b></small>`
             }

            html += `</a>`
        });
        $("#searchResultsList").empty().append(html);
        $("#resultCount").text(`${data.length} Results`)
    })
});


</script>
