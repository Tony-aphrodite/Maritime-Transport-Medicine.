# 🧪 CURP Validation Testing Guide

## **Overview**
Complete testing guide for the VerificaMex CURP validation integration in the Maritime Transport Medicine project.

## **Test Configuration**

### **API Credentials**
- **Provider**: VerificaMex Company
- **Documentation**: https://docs.verificamex.com/identity/
- **Token Type**: JWT Bearer Token
- **Token Expires**: 2026-01-01 (Valid for 1+ years)

### **Environment Setup**
```bash
# Add to your .env file
VERIFICAMEX_TOKEN=eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYWVlNjExZWEyN2M0MzM2ZjgzOWI1NTQ1MjVlZTQ3ZTI4MTJlYTRiMGQ3MTQ3Yjk0MDdkYjdhNjhjNjFkNWYxZDhmNDgxMjBiYzdhM2FkODIiLCJpYXQiOjE3NjMwODQ3ODIuMjAwNDYxLCJuYmYiOjE3NjMwODQ3ODIuMjAwNDk5LCJleHAiOjE3OTQ2MjA3ODIuMTg2MTEsInN1YiI6IjgxNzQiLCJzY29wZXMiOltdfQ.UTZKx5J3-w1iH6z6EcwQbFgjCNL5U57rjLXQ_pLK__wva8-4icxvxikICqRVrNIzjLYu5WpETi-2wpg4Qh3W_0MbgVyma854mI2AF_Bffbaf3X6e-UfOelYwIsk6FD1iJrPzETNWZCUqSFkEYI_o9F2-g2tdtbf2pGw4-7CqGVef1n3utJPpftK9P4Q6L5t3q8rg-rY6u22enExNEO6-xAP2ZjhkWmEU1J1rzCtD4KcdWY1zOK6zgYEA-NW0Aobay67Dnhkf-m3zsTRleKK6M0CGGjV89AOlZ186bBx1nHqw3g2nVf_5cl6q9s-RraYDXoXO8ppR0U76bV3lBesoG7_9y8V4aIoZxI8uA-Wp4jYoqsCN8KdUE4lHNG4vyaiOvl23dfcoUs2ELSwe-xNK_JCqEBZV1cRF0qzF7_0V1buKMDAI_43TxPMJ2LFkVFz2nGWyVMd88uKijA-OXS-R1KgvikYJt8s3OH8XvV3SWr4PhlGp1uXiOdxgXbRVmcYbJcxmvEvlwQTk0TdEKUDSDaVvF3kJHom-4ddoA-nMiQx-mtY31l05V01346pm2-5K-sXnQxpjaSRjjIWRHhb9FG09NeHVUCtjc7ApQq7RSSeo8KuEVOHoX5kWsY5H7820D4HqFnqq_7UEyuQCGsLlgk4A2SUxRhiXq2suTc86n-k
VERIFICAMEX_BASE_URL=https://api.verificamex.com
```

## **Test Data**

### **Valid Test CURP**
- **CURP**: `RICJ830716HTSSNN05`
- **Expected Data**:
  - Birth Date: July 16, 1983
  - Gender: Male (H)
  - State: Sinaloa (SS)
  - Valid format: ✅

### **Additional Test CURPs**
```
PEGJ850415HDFRRN05  # Original example CURP
GAMA850127MDFRNNA7  # Another valid format example
INVALID123456789    # Invalid format test
ABC                 # Too short test
ABCD1234567890ABCD123 # Too long test
```

## **Testing Procedures**

### **1. Pre-Testing Setup**
```bash
# 1. Start your Laravel development server
php artisan serve

# 2. Copy environment file and configure token
cp .env.example .env
# Edit .env with the provided VerificaMex token

# 3. Clear application cache
php artisan config:clear
php artisan cache:clear
```

### **2. Frontend Testing**

#### **A. Dedicated CURP Validation Page**
1. **Navigate to**: `http://localhost:8000/curp/validate`
2. **Pre-populated Test**: Page loads with `RICJ830716HTSSNN05`
3. **Test Steps**:
   - ✅ Verify the CURP is pre-filled
   - ✅ Click "Validar CURP" button
   - ✅ Observe loading state
   - ✅ Check success/error response
   - ✅ Verify detailed information display

#### **B. Registration Page Integration**
1. **Navigate to**: `http://localhost:8000/registro`
2. **Find CURP Field**: Section 1 - Información General
3. **Test Steps**:
   - ✅ Enter test CURP: `RICJ830716HTSSNN05`
   - ✅ Verify real-time format validation
   - ✅ Check visual feedback (green/red borders)
   - ✅ Click "Validar CURP" button (opens new tab)
   - ✅ Complete validation in new tab

### **3. Backend API Testing**

#### **A. Direct API Endpoint Testing**
```bash
# Test CURP validation endpoint
curl -X POST http://localhost:8000/curp/validate \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: YOUR_CSRF_TOKEN" \
  -d '{"curp":"RICJ830716HTSSNN05"}'

# Expected Response:
{
  "success": true,
  "message": "CURP válido y verificado exitosamente contra RENAPO",
  "data": {
    "curp": "RICJ830716HTSSNN05",
    "valid": true,
    "details": {
      "nombres": "...",
      "primerApellido": "...",
      "fechaNacimiento": "1983-07-16",
      "sexo": "MASCULINO",
      "entidadNacimiento": "SINALOA"
    }
  }
}
```

#### **B. Format Validation Testing**
```bash
# Test format validation endpoint
curl -X POST http://localhost:8000/curp/validate-format \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: YOUR_CSRF_TOKEN" \
  -d '{"curp":"RICJ830716HTSSNN05"}'

# Expected Response:
{
  "valid": true,
  "message": "Formato de CURP válido"
}
```

### **4. Error Handling Testing**

#### **A. Invalid Format Tests**
| Test Case | Input | Expected Result |
|-----------|--------|-----------------|
| Too Short | `ABCD123` | Format error |
| Too Long | `ABCD1234567890ABCD123` | Format error |
| Invalid Characters | `RICJ830716HTSS!!05` | Format error |
| Wrong Pattern | `1234567890ABCDEF12` | Format error |

#### **B. API Error Tests**
1. **Invalid Token**: Test with wrong bearer token
2. **Network Error**: Test without internet connection
3. **Invalid CURP**: Test with properly formatted but non-existent CURP

### **5. User Interface Testing**

#### **A. Responsive Design**
- ✅ **Desktop** (1920x1080): Full layout
- ✅ **Tablet** (768px): Responsive layout
- ✅ **Mobile** (375px): Mobile-optimized layout

#### **B. Accessibility Testing**
- ✅ **Keyboard Navigation**: Tab through all elements
- ✅ **Screen Reader**: Test with screen reader
- ✅ **Color Contrast**: Verify adequate contrast ratios
- ✅ **Form Labels**: Check proper form labeling

### **6. Performance Testing**

#### **A. Load Testing**
```bash
# Test API response time
time curl -X POST http://localhost:8000/curp/validate \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: YOUR_CSRF_TOKEN" \
  -d '{"curp":"RICJ830716HTSSNN05"}'

# Expected: < 3 seconds response time
```

#### **B. Browser Performance**
- ✅ **JavaScript Load Time**: Check script execution
- ✅ **Form Validation Speed**: Test real-time validation
- ✅ **API Call Efficiency**: Monitor network requests

## **Expected Results**

### **Successful CURP Validation Response**
```json
{
  "success": true,
  "message": "CURP válido y verificado exitosamente contra RENAPO",
  "data": {
    "curp": "RICJ830716HTSSNN05",
    "valid": true,
    "details": {
      "nombres": "RICARDO JAVIER",
      "primerApellido": "RIVERA",
      "segundoApellido": "CASTRO",
      "fechaNacimiento": "1983-07-16",
      "sexo": "MASCULINO",
      "entidadNacimiento": "SINALOA",
      "nacionalidad": "MEXICANA",
      "estatus": "ACTIVO"
    },
    "verification_date": "2024-12-10T...",
    "certificate_url": "https://api.verificamex.com/certificates/..."
  }
}
```

## **Troubleshooting**

### **Common Issues**
1. **Token Expired**: Check token expiry date (valid until 2026)
2. **Network Timeout**: Increase timeout in controller
3. **CSRF Mismatch**: Ensure CSRF token is included
4. **API Rate Limits**: Check VerificaMex API limits

### **Debug Commands**
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Clear all caches
php artisan optimize:clear

# Check routes
php artisan route:list | grep curp
```

### **Log Monitoring**
Monitor these log entries:
- `VerificaMex API Error`: API call failures
- `CURP Validation Error`: General validation errors
- Laravel request logs for debugging

## **Production Checklist**

- ✅ Environment variables configured
- ✅ Token validity confirmed
- ✅ Error handling implemented
- ✅ Logging configured
- ✅ Rate limiting considered
- ✅ SSL/HTTPS enabled
- ✅ CSRF protection active
- ✅ Input sanitization verified

## **Success Criteria**

The CURP validation feature is considered successful when:

1. ✅ Test CURP `RICJ830716HTSSNN05` validates successfully
2. ✅ Real-time format validation works without errors
3. ✅ API responses are received within 3 seconds
4. ✅ Error handling displays user-friendly messages
5. ✅ UI is responsive on all devices
6. ✅ Integration with registration form works seamlessly
7. ✅ Both dedicated page and registration validation function properly

---

**Testing Status**: Ready for comprehensive testing
**Last Updated**: December 2024
**Next Review**: After successful deployment