<div id="backupsBox" class="boxSlide">
<div id="backupsNav" class="mb-2">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" data-target="backupsOverview" aria-current="page" href="#">Instances</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-target="profilesBackupOverview" href="#">Profiles</a>
        </li>
    </ul>
</div>
<?php
    require __DIR__. "/backups/instaces.php";
    require __DIR__. "/backups/profiles.php";
?>
</div>
<script>

function loadBackupsView() {
    $(".boxSlide, #backupContents").hide();
    $("#backupsBox, #backupsOverview").show();
    $("#sidebar-ul").empty();
    $(".sidebar-lg-show").removeClass("sidebar-lg-show");
    loadBackupsOverview();
}

function loadProfilesBackupView() {
    $("#profilesBackupOverview").show();
    loadProfileBackupsOverview();
}


$("#backupsNav").on("click", ".nav-link", function(){
    $("#backupsNav").find(".active").removeClass("active");
    $(this).addClass("active");
    let target = $(this).data("target");
    $(".backupsOverviewBox").hide()
    if(target == "profilesBackupOverview"){
        loadProfilesBackupView();
    }else{
        loadBackupsView()
    }
})

</script>
