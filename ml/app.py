from flask import Flask, request, jsonify
import joblib
import numpy as np

app = Flask(__name__)

model  = joblib.load('model.pkl')
scaler = joblib.load('scaler.pkl')

@app.route('/predict', methods=['POST'])
def predict():
    try:
        data = request.get_json()

        required = ['age', 'income', 'loan_amount', 'credit_score', 'employment_years']
        for field in required:
            if field not in data:
                return jsonify({'error': f'Missing field: {field}'}), 400

        features = np.array([[
            float(data['age']),
            float(data['income']),
            float(data['loan_amount']),
            float(data['credit_score']),
            float(data['employment_years'])
        ]])

        features_scaled = scaler.transform(features)
        prob  = model.predict_proba(features_scaled)[0][1]
        label = 'high' if prob >= 0.5 else 'low'

        return jsonify({
            'risk_score': round(float(prob), 4),
            'label':      label,
            'confidence': round(float(max(model.predict_proba(features_scaled)[0])) * 100, 1)
        })

    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/health', methods=['GET'])
def health():
    return jsonify({'status': 'ok', 'model': 'Logistic Regression', 'version': '1.0'})

if __name__ == '__main__':
    print("🚀 LoanGuard ML API running on port 5000")
    app.run(host='0.0.0.0', port=5000, debug=True)