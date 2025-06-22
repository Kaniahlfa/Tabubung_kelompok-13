const db = require('../db');

const insertSpending = async (req, res) => {
  const { tanggal, jumlah, kategori, metode, catatan } = req.body;

  try {
    const sql = `INSERT INTO spending (tanggal, jumlah, kategori, metode, catatan) VALUES (?, ?, ?, ?, ?)`;
    await db.query(sql, [tanggal, jumlah, kategori, metode, catatan]);
    res.status(201).json({ message: "Spending recorded successfully" });
  } catch (err) {
    res.status(500).json({ message: "Error saving spending", error: err });
  }
};

module.exports = { insertSpending };
