# Sistem Informasi Presensi

Instalation :
1.) git clone https://github.com/edhosan/presensi.git
2.) composer install
3.) npm install

Reconfigure Core Asset :
/Illuminate/Foundation/helpers.php/asset()
Replace : 
function asset($path, $secure = null)
{
    return app('url')->asset("public/".$path, $secure);
}
