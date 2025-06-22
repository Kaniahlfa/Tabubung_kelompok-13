const pool = require('../db');

// CREATE budget
exports.createBudget = async (req, res) => {
  const { category = 'Lainnya', amount = 0, period = 'Tidak Ada', account = 'Default', repeat = false } = req.body;
  try {
    const [result] = await pool.execute(
      `INSERT INTO budgets (category, amount, period, account, repeat_budget)
       VALUES (?, ?, ?, ?, ?)`,
      [category, amount, period, account, repeat ? 1 : 0]
    );
    res.status(201).json({ success: true, id: result.insertId });
  } catch (err) {
    console.error('Create budget error:', err);
    res.status(500).json({ success: false, error: err.message });
  }
};

// GET all budgets
exports.getAllBudgets = async (req, res) => {
  try {
    const [rows] = await pool.execute(`
      SELECT b.id, b.category, b.amount, b.period, b.account, b.repeat_budget, b.created_at,
             COALESCE(SUM(e.amount), 0) AS total_spent
      FROM budgets b
      LEFT JOIN expenses e ON b.id = e.budget_id
      GROUP BY b.id
      ORDER BY b.created_at DESC
    `);
    res.json(rows);
  } catch (err) {
    console.error('Get budgets error:', err);
    res.status(500).json({ error: err.message });
  }
};

// ADD expense
exports.addExpense = async (req, res) => {
  const { budget_id, amount, description = null } = req.body;
  try {
    const [result] = await pool.execute(
      `INSERT INTO expenses (budget_id, amount, description) VALUES (?, ?, ?)`,
      [budget_id, amount, description]
    );
    res.status(201).json({ success: true, id: result.insertId });
  } catch (err) {
    console.error('Add expense error:', err);
    res.status(500).json({ success: false, error: err.message });
  }
};

// GET total remaining budget
exports.getTotalRemainingBudget = async (req, res) => {
  try {
    const [rows] = await pool.execute(`
      SELECT SUM(b.amount - COALESCE(e.total_spent, 0)) AS total_remaining
      FROM budgets b
      LEFT JOIN (
        SELECT budget_id, SUM(amount) AS total_spent
        FROM expenses
        GROUP BY budget_id
      ) e ON b.id = e.budget_id
    `);
    res.json({ total_remaining: rows[0].total_remaining || 0 });
  } catch (err) {
    console.error('Get remaining budget error:', err);
    res.status(500).json({ error: err.message });
  }
};

// DELETE budget
exports.deleteBudget = async (req, res) => {
  const budgetId = req.params.id;
  try {
    const [result] = await pool.execute(`DELETE FROM budgets WHERE id = ?`, [budgetId]);
    if (result.affectedRows > 0) {
      res.json({ success: true });
    } else {
      res.status(404).json({ success: false, message: 'Budget not found' });
    }
  } catch (err) {
    console.error('Delete budget error:', err);
    res.status(500).json({ success: false, error: err.message });
  }
};
