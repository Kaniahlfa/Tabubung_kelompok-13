const db = require('../db');

const insertIncome = async (req, res) => {
  const { tanggal, jumlah, kategori, metode, catatan } = req.body;

  if (!tanggal || !jumlah || !kategori || !metode) {
    return res.status(400).json({ message: "Masukan semua isian yang diminta!" });
  }

  try {
    const sql = `INSERT INTO income (tanggal, jumlah, kategori, metode, catatan) VALUES (?, ?, ?, ?, ?)`;
    const [result] = await db.query(sql, [tanggal, jumlah, kategori, metode, catatan]);
    res.status(201).json({ message: "Income baru berhasil ditambahkan", incomeId: result.insertId });
  } catch (err) {
    res.status(500).json({ message: "Gagal menambahkan income" });
  }
};




module.exports = {
  insertIncome,
};
