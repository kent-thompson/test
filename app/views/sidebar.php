<div id="lone" class="col-left">
    <div id="smenu" class="sidebar stickybox shadow">
        <div class="menuitem" onclick=getIt('indexAuth');>Custom Software</div>
        <div class="menuitem" onclick=getIt('pagetwo');>Computer Languages</div>
        <div class="menuitem" onclick=getIt('pagethree');>General Systems</div>
        <div class="menuitem" onclick=getIt('pagefour');>Microsoft Systems</div>
        <div class="menuitem" onclick=getIt('pagefive');>Databases</div>
        <div class="menuitem" onclick=getIt('pagesix');>Methodologies</div>
        <div class="menuitem" onclick=getIt('pagenine');>Problem Domain</div>
        <div class="menuitem" onclick=getIt('page_sdlc');>SDLC</div>
        <!-- <div class="menuitem" onclick="location.href='glossy2'">Example Website</div> -->
        <div class="menuitem" onclick="location.href='pageseven'">High Tech News</div>
        <div class="menuitem" onclick="location.href='contact'">Contact</div>
        <div class="menuitem" onclick=getIt('userList');>User List</div>
        <div class="menuitem" onclick=logout();>Logout</div>
    </div>
    <div id="skeybox" class="stickybox sidebox smlrtxt">
    Success in computer science comes from striving to be a skilled student of technology and always continue improving. Then working hard to become an expert and teach others to do the same. It can be a way to intellectually grow, prosper and forever stay alert, energized, relevant, enthusiastic and prosperous.
    </div>
    <img id="cpp_logo" src="../res/img/c.svg" width="200" height="200" alt="c++ logo" class="stickybox sidelogo">
</div>
<script>
var basepath = gGetBasepath();

function getIt( purl ) {
    if( sessionStorage.getItem('ktc_token') == '' ) {
        alert( 'NO TOKEN' );
        return false;
    }

    var form = document.createElement('form');
    form.setAttribute('method', 'POST');
    form.setAttribute('action', purl);
    var hfld = document.createElement('input');
    hfld.setAttribute('type', 'hidden');
    hfld.setAttribute('name', 'jwt');
    hfld.setAttribute('value', sessionStorage.getItem('ktc_token'));
    form.appendChild( hfld );
    document.body.appendChild(form);
    form.submit();
    // another example: document.getElementById("myForm").action = "/action_page.php";
}

function logout() {
    if( confirm("LOG OUT of Application?") == true ) {
        sessionStorage.removeItem('ktc_token');
        location.href='index';
    } 
}
</script>
    