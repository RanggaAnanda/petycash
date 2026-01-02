function bukuBesar() {
  const bb = {};
  getJurnal().forEach(j => {
    if (!bb[j.akun]) bb[j.akun] = [];
    bb[j.akun].push(j);
  });
  return bb;
}

function arusKas() {
  return getJurnal().filter(j => COA[j.akun].nama === 'Kas');
}

function neraca() {
  const data = {};
  getJurnal().forEach(j => {
    const akun = COA[j.akun];
    if (!['ASET','LIABILITAS','EKUITAS'].includes(akun.tipe)) return;

    if (!data[akun.tipe]) data[akun.tipe] = 0;
    data[akun.tipe] += j.debit - j.kredit;
  });
  return data;
}


function labaRugi() {
  let pendapatan = 0;
  let beban = 0;
  getJurnal().forEach(j => {
    if (COA[j.akun].tipe === 'PENDAPATAN') pendapatan += j.kredit;
    if (COA[j.akun].tipe === 'BEBAN') beban += j.debit;
  });
  return { pendapatan, beban, laba: pendapatan - beban };
}
