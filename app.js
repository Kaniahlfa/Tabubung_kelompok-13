const express = require('express');
const app = express();
const router = express.Router();
const incomeRoutes = require('./routes/income');
const spendingRoutes = require('./routes/spending');
const transactionRoutes = require('./routes/transaction');
const PORT = 3000;

app.use(express.json());
app.use('/api', incomeRoutes);
app.use('/api', spendingRoutes);
app.use('/api', transactionRoutes);
app.use('/api', budgetRoutes);

app.listen(PORT, () => {
  console.log("REST API listening on port " + PORT);
});
