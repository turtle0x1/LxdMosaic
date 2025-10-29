// Stolen from spice html
function getQueryVar(name, defvalue) {
    var match = RegExp('[?&]' + name + '=([^&]*)')
                      .exec(window.location.search);
    return match ?
        decodeURIComponent(match[1].replace(/\+/g, ' '))
        : defvalue;
}

// Given a hostAlias check if it looks like it contains "https://" because they
// look like junk if they do, return the id instead as it looks better.
// Should encourage users to set alises in the release notes and perhaps make
// it mandatory later.
function hostIdOrAliasForUrl(hostAlias, hostId){
    if(hostAlias.startsWith("https://")){
        return hostId
    }
    return encodeURIComponent(hostAlias); // Encode just incase
}


function makeToastr(x) {
    if(!$.isPlainObject(x)){
        x = JSON.parse(x);
    }

    if(x.hasOwnProperty("responseText")){
        x = JSON.parse(x.responseText);
    }


    if(x.hasOwnProperty("state") && x.hasOwnProperty("message")){
        toastr[x.state](x.message);
    }
    return x;
}

// https://stackoverflow.com/a/14438954/4008082
function onlyUnique(value, index, self) {
  return self.indexOf(value) === index;
}


function formatBytes(bytes,decimals) {
   if(bytes == 0) return '0 Bytes';
   var k = 1024,
       dm = decimals || 2,
       sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
       i = Math.floor(Math.log(bytes) / Math.log(k));
   return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

// Adapted from https://stackoverflow.com/questions/4687723/how-to-convert-minutes-to-hours-minutes-and-add-various-time-values-together-usi
function convertMinsToHrsMins(mins) {
  let h = Math.floor(mins / 60);
  let m = mins % 60;
  h = h < 10 ? '0' + h : h;
  m = m < 10 ? '0' + m : m;
  m = parseFloat(m).toFixed(0);
  return `${h}:${m}`
}

function nanoSecondsToHourMinutes(nanoseconds) {
    return convertMinsToHrsMins(nanoseconds / 60000000000);
}


function nl2br (str, is_xhtml) {
    if (typeof str === 'undefined' || str === null) {
        return '';
    }
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function ajaxRequest(url, data, callback){
    if (typeof userDetails == "object") {
        $.ajaxSetup({
            headers: userDetails
        })
    }

    $.ajax({
         type: 'POST',
         data: data,
         url: url,
         success: function(data, _, jqXHR){
             if(jqXHR.status == 205){
                 $.alert("its likely a host has gone offline, refresh the page");
                 return false;
             }
             callback(data);
         },
         error: function(data){
             if(data.status == 403){
                 location.reload();
             }
             callback(data);
         }
     });
}


function mapObjToSignleDimension(obj, keyToMap)
{
    let output = [];
    Object.keys(obj).map(function(key, index) {
       output.push(obj[key][keyToMap]);
    });
    return output;
}

function createBreadcrumbItemHtml(name, classes, link = "")
{
    if(link !== ""){
        return `<li href="${link}" style="cursor: pointer;" class="breadcrumb-item text-decoration-underline ${classes}" data-navigo>${name}</li>`;
    }else{
        return `<li class="breadcrumb-item ${classes}">${name}</li>`;
    }

}

function setBreadcrumb(name, classes, link)
{
    $(".breadcrumb").empty().append(createBreadcrumbItemHtml(name, classes, link))
    router.updatePageLinks()
}

function addBreadcrumbs(names, classes, preserveRoot = true, links = [])
{
  if(preserveRoot){
      $(".breadcrumb").find(".breadcrumb-item:gt(0)").remove();
  }else{
      $(".breadcrumb").empty();
  }

  $(".breadcrumb").find(".active").removeClass("active");
  let items = "";

  $.each(names, function(i, item){
      let l = typeof links[i] === "undefined" ? "" : links[i]
      items += createBreadcrumbItemHtml(item, classes[i], links[i]);
  })

  $(".breadcrumb").append(items)
}

function changeActiveNav(newActiveSelector)
{
    $("#buttonsNavbar").find(".active").each(function(){
        $(this).removeClass("active");
    })
    $("#mainNav").find(".active").removeClass("active");
    $("#mainNav").find(newActiveSelector).addClass("active");
}

var hostStatusChangeConfirm = null;

function makeServerChangePopup(status, host)
{
    if(hostStatusChangeConfirm !== null && hostStatusChangeConfirm.isOpen()){
        hostStatusChangeConfirm.close();
    }

    let message = "";
    if(status == "offline"){
        message = `If there any requests related to hosts running you
          may need to wait 30 seconds and refresh the page`;
    }else{
        message = "Host is now online"
    }

    hostStatusChangeConfirm = $.confirm({
        title: `${host} is ${status}!`,
        content: message,
        theme: 'dark',
        buttons: {
            ok: {
                btnClass: "btn btn-danger"
            }
        }
    });
}

function getSum(total, num) {
    return parseInt(total) + parseInt(num);
}

var scalesBytesCallbacks = {
  yAxes: [{
    ticks: {
      beginAtZero: true,
      callback: function(value, index, values) {
          return formatBytes(value);
      }
    }
  }]
};

var toolTipsBytesCallbacks = {
    callbacks: {
        label: function(value, data) {
            return formatBytes(data.datasets[value.datasetIndex].data[value.index]);
        }
    }
};

var monthsNameArray = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

// https://stackoverflow.com/questions/1484506/random-color-generator/32124533
function randomColor(format = 'hex') {
    const rnd = Math.random().toString(16).slice(-6);
    if (format === 'hex') {
        return '#' + rnd;
    }
    if (format === 'rgb') {
        const [r, g, b] = rnd.match(/.{2}/g).map(c=>parseInt(c, 16));
        return `rgb(${r}, ${g}, ${b})`;
    }
}
