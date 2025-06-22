const express = require('express');
const router = express.Router();
const { getAllTransactions, deleteAllTransactions } = require('../controllers/transactionController');

router.get('/transactions', getAllTransactions);
router.delete('/transactions', deleteAllTransactions);

module.exports = router;
