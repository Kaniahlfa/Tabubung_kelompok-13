const express = require('express');
const router = express.Router();
const controller = require('../controllers/budgetController');

router.post('/budgets', controller.createBudget);
router.get('/budgets', controller.getAllBudgets);
router.post('/expenses', controller.addExpense);
router.get('/budgets/remaining', controller.getTotalRemainingBudget);
router.delete('/budgets/:id', controller.deleteBudget);

module.exports = router;
