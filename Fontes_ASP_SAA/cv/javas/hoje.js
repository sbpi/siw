/*
====================================================
Este JavaScript foi criado por

J@kWare.NET Inform�tica
http://www.jakware.net

jakware@jakware.net

Proibida a c�pia n�o autorizada deste Script

Estas linhas n�o podem ser apagadas
====================================================
*/

        hoje = new Date()
        hdia = hoje.getDate()
        hdias = hoje.getDay()
        hmes = hoje.getMonth()
        hano = hoje.getYear()
        if (hdia < 10 && hdia != 1)
                hdia = "0" + hdia
        if (hano < 2000)
                hano = "19" + hano
        if (hdia==1) hfim=("o")
           else hfim=("")
        function CriaArray (n) {
        this.length = n }
        
        NomeMes = new CriaArray(12)
       NomeMes[0] = "Janeiro"
        NomeMes[1] = "Fevereiro"
        NomeMes[2] = "Mar�o"
        NomeMes[3] = "Abril"
        NomeMes[4] = "Maio"
        NomeMes[5] = "Junho"
        NomeMes[6] = "Julho"
        NomeMes[7] = "Agosto"
        NomeMes[8] = "Setembro"
        NomeMes[9] = "Outubro"
        NomeMes[10] = "Novembro"
        NomeMes[11] = "Dezembro"
function DiaDeHoje() {
        document.write ("Bras�lia-DF, "+ hdia  + "<sup>"+ hfim +"</sup> de " + NomeMes[hmes] + " de " + hano + ".")
}
