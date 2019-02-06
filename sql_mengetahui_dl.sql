select x.nip,x.nama,Date_format(y.tanggal,'%d-%m-%Y'),z.keperluan,y.ketidakhadiran_id,z.jml_hari from(
select a.id, a.nip,a.nama,c.keperluan,b.tanggal,b.ketidakhadiran_id 
from peg_data_induk a join peg_jadwal b on a.id = b.peg_id
join ketidakhadiran c on b.ketidakhadiran_id = c.id
where month(b.tanggal) = 5 and b.status = 'DL' 
group by ketidakhadiran_id
having count(nip) > 3
)x join peg_jadwal y on y.peg_id = x.id
join ketidakhadiran z on z.id = y.ketidakhadiran_id
where month(y.tanggal) = 5 and y.status = 'DL'  and z.jml_hari > 1
group by ketidakhadiran_id
order by nip



