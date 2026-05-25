import numpy as np
from sklearn.linear_model import LogisticRegression
from sklearn.preprocessing import StandardScaler
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score, classification_report
import joblib

np.random.seed(42)
n = 1000

age              = np.random.randint(18, 65, n)
income           = np.random.randint(15000, 300000, n)
loan_amount      = np.random.randint(5000, 1500000, n)
credit_score     = np.random.randint(300, 850, n)
employment_years = np.random.randint(0, 35, n)

# Risk label logic — realistic rules
risk = np.zeros(n)
risk += (credit_score < 580) * 1.5
risk += (income < 30000) * 1.0
risk += (loan_amount / np.maximum(income, 1) > 5) * 1.2
risk += (employment_years < 2) * 0.8
risk += (age < 25) * 0.4
risk += np.random.normal(0, 0.3, n)  # noise

y = (risk > 1.5).astype(int)  # 1 = high risk

X = np.column_stack([age, income, loan_amount, credit_score, employment_years])

X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

scaler = StandardScaler()
X_train_scaled = scaler.fit_transform(X_train)
X_test_scaled  = scaler.transform(X_test)

model = LogisticRegression(max_iter=1000, random_state=42)
model.fit(X_train_scaled, y_train)

y_pred = model.predict(X_test_scaled)
acc = accuracy_score(y_test, y_pred)

print(f"\n✅ Model trained successfully!")
print(f"📊 Accuracy: {acc * 100:.2f}%")
print(f"\n{classification_report(y_test, y_pred, target_names=['Low Risk', 'High Risk'])}")

joblib.dump(model,  'model.pkl')
joblib.dump(scaler, 'scaler.pkl')
print("💾 model.pkl and scaler.pkl saved!")