--Query per inventario di un Personaggio:
SELECT c.* ,o.Nome, i.Quantita
FROM (	SELECT p.IDPersonaggio, p.NomePersonaggio, p.Livello, p.PuntiVitaMax, p.PuntiVitaAtt, p.PuntiExp, a.NomeArea, r.NomeRazza
        FROM personaggi_giocanti p, razze r, aree a
        WHERE p.NomeGiocatore="Carlo"
        AND p.MondoPresenza = a.Mondo
        AND p.AreaPresenza = a.IDArea
        AND p.Razza = r.IDRazza) c, inventari_pg i, tipi_oggetto o
WHERE c.IDPersonaggio = i.Personaggio
AND i.TipoOggetto = o.IDTipoOgg
