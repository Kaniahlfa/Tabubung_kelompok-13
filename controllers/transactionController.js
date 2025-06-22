const db = require('../db');

const getAllTransactions = async (req, res) => {
  try {
    const [incomeRows] = await db.query("SELECT tanggal, kategori, metode, jumlah FROM income");
    const [spendingRows] = await db.query("SELECT tanggal, kategori, metode, jumlah FROM spending");

    const data = [];

    incomeRows.forEach(row => {
      data.push({ ...row, tipe: 'Income' });
    });
    spendingRows.forEach(row => {
      data.push({ ...row, tipe: 'Spending' });
    });

    data.sort((a, b) => new Date(b.tanggal) - new Date(a.tanggal));

    res.status(200).json(data);
  } catch (err) {
    res.status(500).json({ message: 'Error fetching transactions', error: err });
  }
};

const deleteAllTransactions = async (req, res) => {
  try {
    await db.query("DELETE FROM income");
    await db.query("DELETE FROM spending");
    res.status(200).json({ message: "All transactions deleted" });
  } catch (err) {
    res.status(500).json({ message: "Failed to delete transactions", error: err });
  }
};

module.exports = { getAllTransactions, deleteAllTransactions };
